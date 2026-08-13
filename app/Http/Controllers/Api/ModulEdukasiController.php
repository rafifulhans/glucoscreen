<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Services\DiagnosaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ModulEdukasiController extends Controller
{
    /**
     * Kategori modul edukasi yang tersedia (maksimal 1 modul per kategori).
     */
    public const KATEGORI = [
        'normal'      => 'Normal',
        'prediabetes' => 'Prediabetes',
        'diabetes'    => 'Diabetes',
    ];

    /**
     * Mengembalikan modul edukasi yang tersedia, dikelompokkan per kategori.
     *
     * Setiap modul dilengkapi materi yang terurut — sajikan materi ke-N pada
     * slide ke-N (sesuai tampilan web). `terisi` melaporkan apakah kategori
     * sudah memiliki modul; `semua_terisi` true ketika ketiganya terisi.
     *
     * Opsional terima `?kategori=normal|prediabetes|diabetes` untuk
     * mengembalikan hanya kategori yang sesuai (mis. setelah mobile menentukan
     * kategori risiko pengguna dari nilai GDS).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $slugs = array_keys(self::KATEGORI);
            $filter = $request->query('kategori');

            if ($filter !== null && !in_array($filter, $slugs, true)) {
                return response()->json([
                    'message' => 'Kategori tidak valid. Pilihan: ' . implode(', ', $slugs),
                ], 422);
            }

            // Karena maksimal 1 modul per kategori, keyBy('kategori') aman.
            $moduls = Informasi::with(['materis' => fn ($q) => $q->orderBy('urutan')])
                ->whereIn('kategori', $slugs)
                ->orderBy('kategori')
                ->orderByDesc('updated_at')
                ->get()
                ->keyBy('kategori');

            $all = [];
            foreach (self::KATEGORI as $slug => $label) {
                /** @var \App\Models\Informasi|null $modul */
                $modul = $moduls->get($slug);

                $all[$slug] = [
                    'slug'   => $slug,
                    'label'  => $label,
                    'terisi' => (bool) $modul,
                    'modul'  => $modul ? $this->formatModul($modul) : null,
                ];
            }

            // Hitung status global dari ketiga kategori (konsisten meski filter).
            $jumlahTerisi = count(array_filter($all, fn ($c) => $c['terisi']));

            $kategoriResponse = $filter === null
                ? array_values($all)
                : [$all[$filter]];

            return response()->json([
                'data' => [
                    'kategori'        => $kategoriResponse,
                    'jumlah_kategori' => count(self::KATEGORI),
                    'jumlah_terisi'   => $jumlahTerisi,
                    'semua_terisi'    => $jumlahTerisi >= count(self::KATEGORI),
                ],
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API modul edukasi gagal: ' . $e->getMessage());

            return response()->json([
                'message' => 'Gagal memuat modul edukasi.',
            ], 500);
        }
    }

    /**
     * Menerima POST dari mobile: nilai GDS + gejala klasik, lalu mengembalikan
     * kategori risiko yang sesuai dan modul edukasi untuk kategori tersebut.
     *
     * Kategori ditentukan secara DATA-DRIVEN: threshold diambil dari tabel
     * `informasis` (kolom `gds` tidak kosong & kolom `isi` kosong) melalui
     * DiagnosaService — bukan hardcode. Operator tiap kategori:
     *   - normal      : GDS <  batas normal
     *   - prediabetes : batas prediabetes <= GDS <= batas diabetes
     *   - diabetes    : GDS >= batas diabetes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function diagnose(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama'           => 'required|string|max:255',
                'gds'            => 'required|numeric|min:0',
                // Gejala klasik dikirim sebagai string nama gejala dari mobile (bisa kosong/null)
                'gejala_klasik'  => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $gds        = (float) $request->input('gds');
            $nama       = trim($request->input('nama'));
            // Konversi string gejala ke boolean: ada isinya = true, kosong/null = false
            $gejala     = !empty(trim($request->input('gejala_klasik')));
            $namaGejala = $gejala ? trim($request->input('gejala_klasik')) : null;

            // Kategori ditentukan dari threshold di tabel informasis (data-driven),
            // dengan mempertimbangkan gejala klasik.
            $slug = DiagnosaService::tentukanKategori($gds, $gejala);

            // Ambil modul edukasi sesuai kategori hasil diagnosa.
            $modul = Informasi::where('kategori', $slug)->orderByDesc('updated_at')->first();

            // Endpoint diagnose TIDAK menyimpan data ke database.
            // Data hanya dibaca dan dikembalikan untuk ditampilkan di mobile;
            // mobile yang akan memanggil POST /api/pengunjung untuk menyimpan data lengkap
            // (di sana kategori dihitung ulang server-side sehingga tidak pernah kosong).

            return response()->json([
                'data' => [
                    'kategori'      => $slug,
                    'label'         => self::KATEGORI[$slug] ?? null,
                    'gds'           => $gds,
                    'gejala_klasik' => $namaGejala,
                    'modul'         => $modul ? $this->formatModul($modul) : null,
                    'semua_terisi'  => $this->semuaTerisi(),
                    'saved'         => false, // Tidak ada penyimpanan di endpoint ini
                ],
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API modul diagnose gagal: ' . $e->getMessage());

            return response()->json([
                'message' => 'Gagal menilai kategori pasien.',
            ], 500);
        }
    }

    /**
     * Apakah ketiga kategori sudah memiliki modul.
     */
    protected function semuaTerisi(): bool
    {
        $terisi = Informasi::whereIn('kategori', array_keys(self::KATEGORI))
            ->pluck('kategori')
            ->unique()
            ->count();

        return $terisi >= count(self::KATEGORI);
    }

    /**
     * Memformat satu modul + materi-nya ke struktur response mobile.
     */
    protected function formatModul(Informasi $modul): array
    {
        return [
            'id'                 => $modul->id,
            'judul'              => $modul->judul,
            'kategori'           => $modul->kategori,
            'gds'                => $modul->gds !== null ? (float) $modul->gds : null,
            'gejala_klasik'      => (bool) $modul->gejala_klasik,
            'hasil_pemeriksaan'  => $modul->hasil_pemeriksaan,
            'pesan_utama'        => $modul->pesan_utama,
            'isi'                => $modul->isi,
            'materi'             => $modul->materis
                ->map(fn ($m) => [
                    'urutan' => (int) $m->urutan,
                    'isi'    => $m->isi,
                ])
                ->values(),
        ];
    }
}
