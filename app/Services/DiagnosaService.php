<?php

namespace App\Services;

use App\Models\Informasi;

class DiagnosaService
{
    /**
     * Kategori hasil diagnosa GDS beserta label tampilannya.
     *
     * Ketoasidosis Diabetik adalah kondisi darurat dengan prioritas tertinggi
     * pada nilai GDS tinggi (300-600 mg/dL dengan gejala klasik), sehingga
     * dicek sebelum Diabetes.
     */
    public const KATEGORI = [
        'normal'      => 'Normal',
        'prediabetes' => 'Prediabetes',
        'diabetes'    => 'Diabetes',
        'ketoasidosis' => 'Ketoasidosis Diabetik',
    ];

    /**
     * Menentukan kategori GDS dari nilai input secara data-driven.
     *
     * Threshold TIDAK di-hardcode, melainkan diambil dari tabel `informasis`
     * (hanya baris yang kolom `gds` TIDAK kosong DAN kolom `isi` KOSONG).
     *
     * Aturan kategori (batas normal/prediabetes/diabetes dari database):
     *   a) GDS < batas normal DAN tanpa gejala klasik          -> Normal
     *   b) batas normal <= GDS <= batas prediabetes, ATAU
     *      (GDS < batas normal DAN ada gejala klasik)          -> Prediabetes
     *   c) GDS >= batas diabetes DAN ada gejala klasik         -> Diabetes
     *
     * Contoh: masukkan GDS = 105, batas normal = 126 -> 105 < 126 (true),
     * sehingga masuk ke pengecekan selanjutnya:
     *   - tanpa gejala                        -> Normal
     *   - dengan gejala klasik                -> Prediabetes
     *
     * @param  float  $gds     Nilai GDS dari input mobile (mg/dL)
     * @param  bool   $gejala  Apakah ada gejala klasik (true/false)
     * @return string          Kategori: normal | prediabetes | diabetes | ketoasidosis
     */
    public static function tentukanKategori(float $gds, bool $gejala = false): string
    {
        // Ambil batas GDS per kategori dari database (gds tidak kosong & isi kosong).
        $moduls = Informasi::whereNotNull('gds')
            ->where('gds', '!=', '')
            ->whereIn('kategori', array_keys(self::KATEGORI))
            ->where(function ($query) {
                $query->whereNull('isi')
                    ->orWhere('isi', '');
            })
            ->get(['kategori', 'gds']);

        // Kumpulkan batas GDS per kategori untuk dipakai pada perbandingan.
        $batas = [];
        foreach ($moduls as $modul) {
            $batas[$modul->kategori] = (float) $modul->gds;
        }

        $normal      = $batas['normal'] ?? null;
        $prediabetes = $batas['prediabetes'] ?? null;
        $diabetes    = $batas['diabetes'] ?? null;
        $ketoasidosis = $batas['ketoasidosis'] ?? null;

        // a) GDS < batas normal DAN tanpa gejala klasik -> Normal
        if ($normal !== null && $gds < $normal && !$gejala) {
            return 'normal';
        }

        // b) (normal <= GDS <= prediabetes) ATAU (GDS < normal DAN ada gejala) -> Prediabetes
        $rentangPrediabetes = $normal !== null
            && $prediabetes !== null
            && $gds >= $normal
            && $gds <= $prediabetes;
        $gejalaNormalRendah = $normal !== null && $gds < $normal && $gejala;

        if ($rentangPrediabetes || $gejalaNormalRendah) {
            return 'prediabetes';
        }

        // d) Ketoasidosis Diabetik (KONDISI DARURAT): GDS 300-600 mg/dL
        //    disertai gejala klasik — prioritas di atas Diabetes.
        if ($ketoasidosis !== null
            && $gds >= $ketoasidosis
            && $gds <= 600
            && $gejala) {
            return 'ketoasidosis';
        }

        // c) GDS >= batas diabetes DAN ada gejala klasik -> Diabetes
        if ($diabetes !== null && $gds >= $diabetes && $gejala) {
            return 'diabetes';
        }

        // Fallback: GDS sangat tinggi (>= batas diabetes) tanpa gejala tetap
        // dikategorikan Diabetes sebagai langkah aman/rujukan.
        if ($diabetes !== null && $gds >= $diabetes) {
            return 'diabetes';
        }

        // Fallback terakhir bila tidak ada modul/kecocokan.
        return 'prediabetes';
    }
}
