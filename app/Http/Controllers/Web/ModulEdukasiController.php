<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\Materi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ModulEdukasiController extends Controller
{
    /**
     * Kategori modul edukasi yang tersedia.
     */
    const KATEGORI = [
        'normal'      => 'Normal',
        'prediabetes' => 'Prediabetes',
        'diabetes'    => 'Diabetes',
    ];

    /**
     * Menampilkan halaman modul edukasi beserta daftar modul.
     */
    public function index()
    {
        $moduls = Informasi::with('materis')
                           ->whereIn('kategori', array_keys(self::KATEGORI))
                           ->orderBy('kategori')
                           ->orderByDesc('updated_at')
                           ->get();

        $kelompok = [];
        foreach (self::KATEGORI as $slug => $label) {
            $kelompok[$slug] = [
                'label'  => $label,
                'moduls' => $moduls->where('kategori', $slug)->values(),
            ];
        }

        // Kategori yang sudah memiliki modul (maksimal 1 per kategori).
        $taken = Informasi::whereIn('kategori', array_keys(self::KATEGORI))
                          ->pluck('kategori')
                          ->unique()
                          ->values()
                          ->all();

        return view('dashboard.pages.modul', [
            'kategori' => self::KATEGORI,
            'kelompok' => $kelompok,
            'taken'    => $taken,
        ]);
    }

    /**
     * Menyimpan modul materi baru beserta materi pendidikan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'              => 'required|min:3|max:255',
            'kategori'           => 'required|in:normal,prediabetes,diabetes',
            'gejala_klasik'      => 'required|in:0,1',
            'gds'                => 'required|numeric|min:0',
            'hasil_pemeriksaan'  => 'required|min:3',
            'pesan_utama'        => 'required|min:3',
            'isi'                => 'nullable|string',
            'materi_isi'         => 'required|array|min:1',
            'materi_isi.*'       => 'required|string',
        ]);

        // Maksimal 1 modul per kategori.
        if (Informasi::where('kategori', $request->kategori)->exists()) {
            return back()
                ->withErrors(['kategori' => 'Modul untuk kategori ' . self::KATEGORI[$request->kategori] . ' sudah ada. Maksimal 1 modul per kategori.'])
                ->withInput();
        }

        $modul = Informasi::create([
            'judul'              => $request->judul,
            'kategori'           => $request->kategori,
            'gejala_klasik'      => $request->gejala_klasik,
            'gds'                => $request->gds,
            'hasil_pemeriksaan'  => $request->hasil_pemeriksaan,
            'pesan_utama'        => $request->pesan_utama,
            'isi'                => $request->isi ?? '',
        ]);

        $this->simpanMateri($modul, $request->materi_isi);

        Alert::success('Berhasil', 'Modul edukasi berhasil ditambahkan!');
        return redirect()->route('modul');
    }

    /**
     * Memperbarui modul materi beserta materi pendidikan.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'              => 'required|min:3|max:255',
            'kategori'           => 'required|in:normal,prediabetes,diabetes',
            'gejala_klasik'      => 'required|in:0,1',
            'gds'                => 'required|numeric|min:0',
            'hasil_pemeriksaan'  => 'required|min:3',
            'pesan_utama'        => 'required|min:3',
            'isi'                => 'nullable|string',
            'materi_isi'         => 'required|array|min:1',
            'materi_isi.*'       => 'required|string',
        ]);

        $modul = Informasi::findOrFail($id);

        // Maksimal 1 modul per kategori (tidak boleh tabrakan dengan modul lain).
        $conflict = Informasi::where('kategori', $request->kategori)
                            ->where('id', '!=', $modul->id)
                            ->exists();
        if ($conflict) {
            return back()
                ->withErrors(['kategori' => 'Kategori ' . self::KATEGORI[$request->kategori] . ' sudah diisi modul lain. Maksimal 1 modul per kategori.'])
                ->withInput();
        }

        $modul->judul              = $request->judul;
        $modul->kategori           = $request->kategori;
        $modul->gejala_klasik      = $request->gejala_klasik;
        $modul->gds                = $request->gds;
        $modul->hasil_pemeriksaan  = $request->hasil_pemeriksaan;
        $modul->pesan_utama        = $request->pesan_utama;
        $modul->isi                = $request->isi ?? '';
        $modul->save();

        $this->simpanMateri($modul, $request->materi_isi);

        Alert::success('Berhasil', 'Modul edukasi berhasil diupdate!');
        return redirect()->route('modul');
    }

    /**
     * Menghapus modul materi beserta seluruh materinya.
     */
    public function destroy($id)
    {
        Informasi::findOrFail($id)->delete();

        Alert::success('Berhasil', 'Modul edukasi berhasil dihapus!');
        return redirect()->route('modul');
    }

    /**
     * Mengganti seluruh materi milik modul berdasarkan urutan input.
     */
    private function simpanMateri(Informasi $modul, array $materiList): void
    {
        $modul->materis()->delete();

        $urutan = 1;
        foreach ($materiList as $item) {
            if (trim($item) === '') {
                continue;
            }

            Materi::create([
                'informasi_id' => $modul->id,
                'urutan'       => $urutan++,
                'isi'          => $item,
            ]);
        }
    }
}
