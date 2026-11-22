# Perubahan API — Tambah Data GDS untuk Pasien Lama

Fitur: dari **Riwayat Data → Detail Pasien** kader bisa menambah pemeriksaan GDS
baru untuk pasien yang sudah ada tanpa mengisi ulang identitas. Form hanya
meminta **Angka GDS** dan **Gejala Klasik**, lalu hasil langsung ditampilkan dan
otomatis masuk ke riwayat pasien tersebut.

Dokumen ini mencatat endpoint yang dipakai aplikasi dan kemungkinan perubahan
yang perlu dilakukan di backend. Aplikasi saat ini berjalan **tanpa perubahan
API** hanya jika backend sudah memperlakukan `POST /pengunjung` dengan NIK yang
sudah ada sebagai **kunjungan baru** (bukan error 422 duplikat).

---

## 1. Alur aplikasi (yang sudah diimplementasi)

1. `GET /pengunjung/groups` → daftar pasien unik (nama, nik, total_kunjungan, gds_terbaru).
2. Kader pilih pasien → `GET /pengunjung/detail/{nik}` → tampil detail + grafik + riwayat.
3. Tombol **+ Tambah Data GDS** → `POST /pengunjung` (identitas dikirim ulang apa adanya dari `info` detail).
4. `POST /modul/diagnose` (gds + gejala_klasik + nama) → hasil kategori + modul edukasi.
5. Kembali ke detail → `GET /pengunjung/detail/{nik}` dimuat ulang (riwayat & grafik terbarui).

Body `POST /pengunjung` pada mode tambah data (identitas diambil dari
`info` detail, jadi harus lengkap di response detail):

```json
{
  "nama": "Budi Santoso",
  "nik": "3201234567890001",
  "alamat": "Jl. Contoh No. 1",
  "rt": "001",
  "rw": "002",
  "tanggal_kunjungan": "2026-11-23",
  "gds": 187,
  "gejala_klasik": "Mudah Haus (Polidipsia)"
}
```

`gejala_klasik` dikosongkan bila kader memilih "Tidak Ada".

---

## 2. Yang perlu dipastikan / diubah di backend

### 2.1 `POST /pengunjung` harus idempoten per NIK (WAJIB)

Kondisi yang dibutuhkan:

- Jika **NIK belum ada** → buat pengunjung baru + record kunjungan baru (201).
- Jika **NIK sudah ada** → **JANGAN** kembalikan 422 "nik sudah terdaftar";
  tambahkan **record kunjungan baru** (tanggal_kunjungan + gds + gejala_klasik)
  pada pengunjung yang sama, lalu kembalikan 201.

Jika backend memvalidasi `nik` dengan `unique`, ganti validasi menjadi
`unique` hanya untuk kombinasi `nik + tanggal_kunjungan` (atau hapus rule
`unique` pada `nik` dan tangani upsert di controller).

Contoh perubahan (Laravel-style, sesuaikan dengan stack backend):

```php
// Sebelum: memaksa NIK unik
'nik' => 'required|string|unique:pengunjung,nik',

// Sesudah: NIK boleh berulang antar kunjungan, tidak boleh dobel di tanggal sama
'nik' => 'required|string',
'tanggal_kunjungan' => 'required|date|
    unique:pengunjung,tanggal_kunjungan,NULL,id,nik,' . $request->nik,
```

Lalu di controller: `Pengunjung::firstOrCreate(['nik' => $nik], [...identitas...])`
dan simpan baris kunjungan terpisah, atau `updateOrCreate(['nik' => $nik,
'tanggal_kunjungan' => $date], [...gds, gejala...])` sesuai desain tabel.

> **Cek dulu**: bila tabel `pengunjung` sudah menyimpan satu baris = satu
> kunjungan (tidak ada tabel `kunjungan` terpisah), maka backend cukup
> menghapus rule `unique:nik` dan membiarkan baris baru dibuat. Tidak ada
> endpoint baru yang diperlukan.

### 2.2 `GET /pengunjung/detail/{nik}` harus mengembalikan `rt` & `rw` (WAJIB)

Aplikasi memakai `data.info` untuk mengisi ulang body `POST /pengunjung`.
Field yang dibaca: `nama`, `nik`, `alamat`, `rt`, `rw`.
Jika `rt`/`rw` tidak ada di response, field tersebut terkirim kosong (masih
diterima, tapi sebaiknya dilengkapi).

Response yang diharapkan:

```json
{
  "data": {
    "info": {
      "nama": "Budi Santoso",
      "nik": "3201234567890001",
      "alamat": "Jl. Contoh No. 1",
      "rt": "001",
      "rw": "002",
      "gds_terbaru": 187,
      "gds_trend": "naik",
      "total_kunjungan": 4
    },
    "chart": { "labels": ["01/09/26", "15/10/26"], "data": [140, 187] },
    "history": [
      {
        "tanggal_kunjungan": "2026-11-23",
        "tanggal_formatted": "23 November 2026",
        "gds": 187,
        "gejala_klasik": "Mudah Haus (Polidipsia)",
        "kategori": "Diabetes"
      }
    ]
  }
}
```

- `history` **wajib terurut terbaru → terlama** dan ikut memuat record yang baru
  saja dibuat, agar tampilan detail langsung benar setelah kembali dari form.
- `chart` dihitung ulang setiap kali detail diminta (jangan di-cache).

### 2.3 (Opsional) Endpoint khusus kunjungan

Jika backend lebih nyaman memisahkan, sediakan endpoint baru dan aplikasi
tinggal diarahkan ke sana di `lib/api_service.dart`:

```
POST /pengunjung/{nik}/kunjungan
Body: { "tanggal_kunjungan": "2026-11-23", "gds": 187, "gejala_klasik": "..." }
```

Perubahan sisi aplikasi hanya: tambah `ApiService.tambahKunjungan()`, lalu
ganti pemanggilan `createPengunjung` di `_simpanData`
(`lib/form_skrining_page.dart`) saat `_tambahMode == true`. Beri tahu bila
endpoint ini yang dipilih.

### 2.4 Sinkronisasi klasifikasi (cek)

Mode tambah data menampilkan kategori dari `POST /modul/diagnose`
(`data.kategori` / `data.label`) dengan fallback ke `DecisionTree` lokal:

- `gds >= 200` → Diabetes
- `gds 126–199` → Prediabetes, atau `gds <= 125` **dengan gejala** → Prediabetes
- `gds <= 125` tanpa gejala → Normal

Pastikan rule di `POST /modul/diagnose` sama persis, agar riwayat
(`history[].kategori`) konsisten dengan hasil yang tampil di layar.

---

## 3. Ringkasan tindakan backend

| # | Item | Prioritas |
|---|------|-----------|
| 1 | `POST /pengunjung` idempoten per NIK (tambah kunjungan, jangan 422) | Wajib |
| 2 | `GET /pengunjung/detail/{nik}` sertakan `rt` & `rw` di `info` | Wajib |
| 3 | `history` detail terurut terbaru → terlama & selalu fresh | Wajib |
| 4 | (Opsional) `POST /pengunjung/{nik}/kunjungan` | Opsional |
| 5 | Samakan rule `POST /modul/diagnose` dengan klasifikasi lokal | Cek |

## 4. Cara uji cepat

```bash
BASE=https://glucoscreen.vercel.app/api/api
TOKEN=<token-login>

# 1) Tambah kunjungan untuk NIK yang sudah ada -> harus 201, bukan 422
curl -s -X POST "$BASE/pengunjung" \
  -H "Authorization: Bearer $TOKEN" -H 'Content-Type: application/json' \
  -d '{"nama":"Budi","nik":"3201234567890001","alamat":"Jl. Contoh 1","rt":"001","rw":"002","tanggal_kunjungan":"2026-11-23","gds":187,"gejala_klasik":"Mudah Haus (Polidipsia)"}'

# 2) Detail pasien -> info.rt/rw terisi, history memuat kunjungan baru
curl -s "$BASE/pengunjung/detail/3201234567890001" -H "Authorization: Bearer $TOKEN"
```

## 5. Detail implementasi di aplikasi

| File | Perubahan |
|------|-----------|
| `lib/pengunjung_detail_page.dart` | Tombol `+ Tambah Data GDS` → buka form dengan `initialData: _info`; `_load()` dipanggil ulang setelah kembali |
| `lib/form_skrining_page.dart` | Param opsional `initialData`; bila terisi, field identitas disembunyikan & diganti kartu read-only; tombol jadi "Proses Skrining" |
| `lib/hasil_kategori_page.dart` | Param `kembaliKeBeranda` (default true). Mode tambah = false → tombol "KEMBALI KE RIWAYAT" (pop 1 layar) |
| `lib/api_service.dart` | Tidak berubah (memakai `createPengunjung` + `diagnose` yang sudah ada) |


