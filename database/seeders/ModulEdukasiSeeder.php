<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Informasi;

class ModulEdukasiSeeder extends Seeder
{
    public function run(): void
    {
        $moduls = [
            [
                'judul'              => 'Modul 1 (Kategori Normal)',
                'kategori'           => 'normal',
                'gds'                => 95,
                'gejala_klasik'      => false,
                'hasil_pemeriksaan'  => 'Kadar GDS < 126 mg/dL (Tanpa Gejala Klasik)',
                'pesan_utama'        => 'Selamat! Kadar gula darah Anda saat ini NORMAL. Mari bersama-sama kita jaga agar tetap stabil dan terhindar dari risiko diabetes di masa depan!',
                'isi'                => 'Yuk, Atur Isi Piringmu!
Bagi piring makanmu menjadi 3 bagian: 1/2 piring berisi sayur dan buah-buahan segar, 1/4 piring berisi karbohidrat (nasi/jagung/ubi), dan 1/4 piring berisi lauk pauk berprotein (ikan, tempe, tahu, atau dada ayam).
Kurangi konsumsi minuman kemasan manis, boba, teh manis berlebih, dan makanan olahan tinggi gula.

Ayo Bergerak Aktif Setiap Hari!
Sempatkan beraktivitas fisik minimal 30 menit sehari (atau total 150 menit dalam seminggu).
Pilih kegiatan yang menyenangkan: jalan santai di pagi hari, berkebun, menyapu, atau bersepeda di sekitar lingkungan.

Jaga Berat Badan Tetap Ideal
Pantau berat badanmu agar tetap seimbang. Targetkan Indeks Massa Tubuh (IMT) ideal berada pada rentang 18,5 - 22,9 Kg/m2.

Lakukan Pemeriksaan Rutin
Meskipun hasil saat ini normal, tetap lakukan cek gula darah ulang bersama Kader Kesehatan minimal 1 bulan sekali.',
            ],
            [
                'judul'              => 'Modul 2 (Kategori Prediabetes)',
                'kategori'           => 'prediabetes',
                'gds'                => 150,
                'gejala_klasik'      => false,
                'hasil_pemeriksaan'  => 'Kadar GDS 126-199 mg/dL',
                'pesan_utama'        => 'Waspada Siaga! Kadar Gula Darah Anda Berada DI ATAS NORMAL, Tetapi Anda BELUM Terkena Diabetes. Mari Cegah Dari Sekarang Supaya Gula Darah Anda Bisa Kembali Normal!',
                'isi'                => 'Targetkan Penurunan Berat Badan (Bagi yang memiliki BB berlebih)
Turunkan berat badan secara bertahap sebesar 5% - 7% dari berat badan saat ini dalam waktu 6 bulan.

Kelola Kalori
Kurangi porsi makanan harian sebesar 500 - 1.000 kkal/hari dari porsi biasanya.
Ganti total minuman manis (es teh manis, kopi sachet, sirup) dengan Air Putih Murni.

Olahraga Terstruktur
Lakukan olahraga aerobik sedang sebanyak 5 kali seminggu (30 menit per sesi).
Pilihan olahraga terbaik: jalan cepat, senam aerobik, bersepeda, atau berenang. Pastikan tubuh berkeringat dan denyut jantung meningkat sedang.

Stop Merokok!
Bahan kimia dalam rokok memperburuk kerja insulin dalam tubuh. Segera kurangi dan hentikan kebiasaan merokok demi melindungi pembuluh darah Anda.',
            ],
            [
                'judul'              => 'Modul 3 (Kategori Diabetes)',
                'kategori'           => 'diabetes',
                'gds'                => 250,
                'gejala_klasik'      => true,
                'hasil_pemeriksaan'  => 'Kadar GDS >=200 mg/dL + Disertai Gejala Klasik (Sering Kencing, Sering Haus, Cepat Lapar, Berat Badan Turun Drastis)',
                'pesan_utama'        => 'Kadar gula darah anda saat ini sangat tinggi, anda masuk kategori Diabetes! Kondisi Diabetes dapat dikendalikan dengan baik jika kita disiplin',
                'isi'                => 'Pilar 1: Pahami dan Jangan Takut
Pahami bahwa diabetes bukan akhir dari segalanya. Dengan pola hidup yang tepat, penderita diabetes tetap bisa beraktivitas secara produktif dan sehat.

Pilar 2: Disiplin Pola Makan 3J (Jumlah, Jenis, Jadwal)
Bagi porsi makan menjadi 3 kali makan besar dan 2-3 kali selingan ringan.
Kurangi nasi putih, ganti dengan karbohidrat kompleks (nasi merah/oatmeal), dan hindari makanan manis/gorengan.

Pilar 3: Latihan Fisik Teratur
Tetap aktif bergerak 30 menit sehari. Pilih olahraga ringan-sedang yang tidak memicu cedera pada kaki.

Pilar 4: Segera Konsultasi Medis & Pengobatan
Datang ke fasilitas kesehatan untuk mendapatkan konfirmasi pemeriksaan dokter dan petunjuk pengobatan yang tepat. Patuhi minum obat sesuai dosis yang dianjurkan tenaga medis.

PERINGATAN KESELAMATAN!
Kenali tanda bahaya gula darah rendah (hipoglikemia <70 mg/dL) dengan ciri-ciri keringat dingin, badan gemetar, pusing, jantung berdebar, mata berkunang-kunang sampai lemas. Segera berikan pertolongan dengan menggunakan 2-3 butir permen manis atau minum 1 gelas air gula.
Periksa telapak dan sela-sela jari kaki setiap hari, jangan biarkan ada luka sekecil apa pun, lecet atau kapalan.',
            ],
        ];

        foreach ($moduls as $modul) {
            $modulModel = Informasi::updateOrCreate(
                ['kategori' => $modul['kategori']],
                $modul
            );

            // Bangun materi pendidikan (multiple items) dari isi modul.
            $modulModel->materis()->delete();

            $blocks = preg_split('/\R\R+/', trim($modul['isi'] ?? ''));
            $urutan = 1;
            foreach ($blocks as $block) {
                $block = trim($block);
                if ($block === '') {
                    continue;
                }
                \App\Models\Materi::create([
                    'informasi_id' => $modulModel->id,
                    'urutan'       => $urutan++,
                    'isi'          => nl2br(e($block)),
                ]);
            }
        }
    }
}
