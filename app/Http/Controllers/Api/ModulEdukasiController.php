<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
     * Threshold (sejalan dengan modul yang ada):
     *   - GDS < 126                  -> normal
     *   - 126 <= GDS <= 199          -> prediabetes
     *   - GDS >= 200                 -> diabetes
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

            $gds       = (float) $request->input('gds');
            $nama      = trim($request->input('nama'));
            // Konversi string gejala ke boolean: ada isinya = true, kosong/null = false
            $gejala    = !empty(trim($request->input('gejala_klasik')));
            $namaGejala = $gejala ? trim($request->input('gejala_klasik')) : null;

            // Tentukan kategori dengan memeriksa data modul di database
            // Ambil semua modul yang ada (dengan isi tidak kosong) untuk menentukan kategori
            $moduls = Informasi::whereNotNull('isi')
                ->where('isi', '!=', '')
                ->whereIn('kategori', array_keys(self::KATEGORI))
                ->get(['kategori', 'gds', 'gejala_klasik']);
            
            // Tentukan kategori berdasarkan kecocokan kondisi di database
            $slug = $this->tentukanKategoriDariModul($gds, $gejala, $moduls);
            
            // Ambil modul edukasi berdasarkan kategori yang telah ditentukan
            $modulQuery = Informasi::where('kategori', $slug);
            
            // Filter berdasarkan kriteria spesifik setiap kategori
            switch ($slug) {
                case 'normal':
                    // Normal: GDS <= 125 & gejala_klasik = false
                    $modulQuery->where('gds', '<=', 125)->where('gejala_klasik', false);
                    break;
                    
                case 'prediabetes':
                    // Prediabetes: GDS <= 199 & gejala_klasik = false
                    $modulQuery->where('gds', '<=', 199)->where('gejala_klasik', false);
                    break;
                    
                case 'diabetes':
                    // Diabetes: GDS >= 200 & gejala_klasik = true
                    $modulQuery->where('gds', '>=', 200)->where('gejala_klasik', true);
                    break;
            }
            
            $modul = $modulQuery->orderByDesc('updated_at')->first();

            // Endpoint diagnose TIDAK menyimpan data ke database
            // Data hanya dibaca dan dikembalikan untuk ditampilkan di mobile
            // Mobile yang akan memanggil POST /api/pengunjung untuk menyimpan data lengkap

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
     * Menentukan kategori risiko dari nilai GDS dan gejala klasik dengan memeriksa
     * data modul yang tersedia di database.
     *
     * Metode ini mengambil semua modul dari tabel informasis yang memiliki isi,
     * lalu mencocokkan nilai GDS dan gejala_klasik dengan kondisi di database.
     *
     * @param  float  $gds
     * @param  bool   $gejala
     * @param  \Illuminate\Database\Eloquent\Collection  $moduls
     * @return string
     */
    protected function tentukanKategoriDariModul(float $gds, bool $gejala, $moduls): string
    {
        // Group moduls by kategori untuk memudahkan pencocokan
        $modulsByKategori = [];
        foreach ($moduls as $modul) {
            $kategori = $modul->kategori;
            if (!isset($modulsByKategori[$kategori])) {
                $modulsByKategori[$kategori] = [];
            }
            $modulsByKategori[$kategori][] = $modul;
        }

        // Cek setiap kategori untuk menemukan yang cocok
        // Prioritas: normal -> prediabetes -> diabetes
        
        // 1. Cek kategori normal
        if (isset($modulsByKategori['normal'])) {
            foreach ($modulsByKategori['normal'] as $modul) {
                // Cek apakah GDS dan gejala cocok dengan kriteria modul
                if ($modul->gds !== null && $gds <= $modul->gds && !$gejala) {
                    return 'normal';
                }
            }
            // Fallback untuk normal: GDS <= 125 dan tidak ada gejala
            if ($gds <= 125 && !$gejala) {
                return 'normal';
            }
        }

        // 2. Cek kategori prediabetes
        if (isset($modulsByKategori['prediabetes'])) {
            foreach ($modulsByKategori['prediabetes'] as $modul) {
                // Cek apakah GDS dan gejala cocok dengan kriteria modul
                if ($modul->gds !== null && $gds >= 126 && $gds <= $modul->gds && !$gejala) {
                    return 'prediabetes';
                }
            }
            // Fallback untuk prediabetes: 126 <= GDS <= 199 dan tidak ada gejala
            if ($gds >= 126 && $gds <= 199 && !$gejala) {
                return 'prediabetes';
            }
        }

        // 3. Cek kategori diabetes
        if (isset($modulsByKategori['diabetes'])) {
            foreach ($modulsByKategori['diabetes'] as $modul) {
                // Cek apakah GDS dan gejala cocok dengan kriteria modul
                if ($modul->gds !== null && $gds >= $modul->gds && $gejala) {
                    return 'diabetes';
                }
            }
            // Fallback untuk diabetes: GDS >= 200 (dengan atau tanpa gejala)
            if ($gds >= 200) {
                return 'diabetes';
            }
        }

        // 4. Fallback jika tidak ada yang cocok
        // Prioritas: diabetes > prediabetes > normal
        if ($gds >= 200) {
            return 'diabetes';
        }
        
        if ($gds >= 126 && $gds <= 199) {
            return 'prediabetes';
        }
        
        // Default fallback
        return 'prediabetes';
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
