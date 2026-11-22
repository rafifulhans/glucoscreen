<?php

namespace Tests\Feature;

use App\Models\Informasi;
use App\Models\Kader;
use App\Models\Pengunjung;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Kontrak API untuk fitur mobile "Tambah Data GDS" (pasien lama):
 *  - POST /pengunjung idempoten per (nik, tanggal_kunjungan, kader): NIK lama
 *    tidak boleh 422, cukup menambah/memperbarui baris kunjungan.
 *  - GET /pengunjung/detail/{nik} mengembalikan rt & rw di `info` + riwayat
 *    terurut terbaru -> terlama.
 */
class PengunjungTambahDataGdsTest extends TestCase
{
    use RefreshDatabase;

    private Kader $kader;

    protected function setUp(): void
    {
        parent::setUp();

        // APP_URL lokal tanpa skema (`glucoscreen.test`) membuat URL helper test
        // menghasilkan path tak valid; pakai host eksplisit untuk test HTTP.
        config(['app.url' => 'http://localhost']);

        // Threshold GDS data-driven dari tabel informasis.
        foreach ([['normal', 126], ['prediabetes', 199], ['diabetes', 200]] as [$slug, $gds]) {
            Informasi::create([
                'judul' => "Modul $slug",
                'kategori' => $slug,
                'gds' => $gds,
                'gejala_klasik' => 0,
                'isi' => '',
            ]);
        }

        $pemimpin = null;
        $posyandu = null;
        $user = null;

        // Model User di aplikasi ini memakai kolom `name` (bukan `nama`) dan
        // `$fillable` tidak memuatnya, jadi dibuat tanpa guard.
        User::unguarded(function () use (&$pemimpin, &$posyandu, &$user) {
            $pemimpin = User::create([
                'name' => 'Pemimpin',
                'username' => 'pemimpin',
                'password' => bcrypt('secret'),
                'readable_password' => 'secret',
                'role' => 'pemimpin',
            ]);

            $posyandu = Posyandu::create([
                'nama' => 'Posyandu Uji',
                'alamat' => 'Jl. Uji',
                'user_id' => $pemimpin->id,
            ]);

            $user = User::create([
                'name' => 'Kader',
                'username' => 'kader',
                'password' => bcrypt('secret'),
                'readable_password' => 'secret',
                'role' => 'kader',
            ]);
        });

        $this->kader = Kader::create([
            'user_id' => $user->id,
            'pemimpin_user_id' => $pemimpin->id,
        ]);

        $this->assertNotNull($posyandu->id);
        Sanctum::actingAs($this->kader);
    }

    private function body(array $overrides = []): array
    {
        return array_merge([
            'nama' => 'Budi Santoso',
            'nik' => '3201234567890001',
            'alamat' => 'Jl. Contoh No. 1',
            'rt' => '001',
            'rw' => '002',
            'tanggal_kunjungan' => '2026-11-23',
            'gds' => 187,
            'gejala_klasik' => 'Mudah Haus (Polidipsia)',
        ], $overrides);
    }

    public function test_rule_diagnose_konsisten_dengan_klasifikasi_lokal(): void
    {
        $kasus = [
            ['gds' => 105, 'gejala' => null, 'harapan' => 'normal'],
            ['gds' => 125, 'gejala' => 'Mudah Haus (Polidipsia)', 'harapan' => 'prediabetes'],
            ['gds' => 150, 'gejala' => null, 'harapan' => 'prediabetes'],
            ['gds' => 187, 'gejala' => 'Sering Berkemih (Poliuria)', 'harapan' => 'prediabetes'],
            ['gds' => 200, 'gejala' => 'Sering Berkemih (Poliuria)', 'harapan' => 'diabetes'],
            ['gds' => 210, 'gejala' => null, 'harapan' => 'diabetes'],
        ];

        foreach ($kasus as $k) {
            $this->postJson('/api/modul/diagnose', [
                'nama' => 'Budi Santoso',
                'gds' => $k['gds'],
                'gejala_klasik' => $k['gejala'],
            ])
                ->assertStatus(200)
                ->assertJsonPath('data.kategori', $k['harapan']);
        }
    }

    public function test_nik_lama_pada_tanggal_baru_menambah_kunjungan_bukan_422(): void
    {
        $first = $this->postJson('/api/pengunjung', $this->body(['tanggal_kunjungan' => '2026-10-01']));
        $first->assertStatus(201);

        // NIK sama, tanggal berbeda -> kunjungan baru (tidak 422 duplikat).
        $this->postJson('/api/pengunjung', $this->body())
            ->assertStatus(201)
            ->assertJsonPath('data.gds', 187);

        $this->assertSame(2, Pengunjung::where('nik', '3201234567890001')->count());
    }

    public function test_kirim_ulang_nik_dan_tanggal_sama_tidak_menduplikasi(): void
    {
        $this->postJson('/api/pengunjung', $this->body())->assertStatus(201);
        $this->postJson('/api/pengunjung', $this->body(['gds' => 145]))->assertStatus(201);

        $rows = Pengunjung::where('nik', '3201234567890001')->get();
        $this->assertCount(1, $rows);
        $this->assertSame(145.0, (float) $rows->first()->gds);
        // GDS 145 + gejala -> Prediabetes (server-side, bukan dari mobile).
        $this->assertSame('prediabetes', $rows->first()->kategori);
    }

    public function test_rt_dan_rw_string_diterima_dan_kembali_di_detail(): void
    {
        $this->postJson('/api/pengunjung', $this->body(['rt' => '001', 'rw' => '002']))
            ->assertStatus(201);

        // rt/rw harus string (bukan batas nilai numerik <= 10).
        $response = $this->getJson('/api/pengunjung/detail/3201234567890001')
            ->assertStatus(200)
            ->assertJsonPath('data.info.rt', '001')
            ->assertJsonPath('data.info.rw', '002')
            ->assertJsonPath('data.info.total_kunjungan', 1);

        $this->assertNotEmpty($response->json('data.history'));
        $this->assertNotEmpty($response->json('data.chart.data'));
    }

    public function test_detail_memuat_kunjungan_baru_terbaru_dulu(): void
    {
        $this->postJson('/api/pengunjung', $this->body(['tanggal_kunjungan' => '2026-10-01', 'gds' => 140]))
            ->assertStatus(201);
        $this->postJson('/api/pengunjung', $this->body(['gds' => 210]))
            ->assertStatus(201);

        $this->getJson('/api/pengunjung/detail/3201234567890001')
            ->assertStatus(200)
            ->assertJsonPath('data.info.total_kunjungan', 2)
            ->assertJsonPath('data.history.0.tanggal_kunjungan', '2026-11-23')
            ->assertJsonPath('data.history.1.tanggal_kunjungan', '2026-10-01');

        // chart terurut naik (terlama -> terbaru) & ikut memuat record baru
        $chart = $this->getJson('/api/pengunjung/detail/3201234567890001')->json('data.chart.data');
        $this->assertSame([140.0, 210.0], array_map('floatval', $chart));
    }
}
