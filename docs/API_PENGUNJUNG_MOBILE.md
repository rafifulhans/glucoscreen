# API Pengunjung (Mobile) — Grouped by NIK

Dokumen ini menyesuaikan kebutuhan mobile: input data pengunjung (skrining baru) dengan tambahan `rt` dan `rw`, serta tampilan riwayat data yang dikelompokkan (grouped) berdasarkan NIK. Yang tampil pada list adalah **NIK dan nama**; setelah diklik, muncul detail pengunjung, grafik GDS, dan riwayat kunjungan.

Semua endpoint di bawah membutuhkan autentikasi `Bearer token` (Sanctum).

## 1. Input Data Pengunjung (Skrining Baru)

`POST /api/pengunjung`

Body (JSON):

```json
{
  "nama": "Budi Santoso",
  "nik": "3201xxxxxxxxxxxx",
  "alamat": "Jl. Mawar No. 1",
  "rt": "001",
  "rw": "002",
  "tanggal_kunjungan": "2026-10-25",
  "gds": 145.5,
  "gejala_klasik": "sering haus, sering buang air kecil"
}
```

Catatan:
- `rt` dan `rw` adalah tambahan baru (opsional, string maks 10).
- `kategori` tidak perlu dikirim; ditentukan server-side dari nilai GDS + gejala klasik.
- `gejala_klasik` bisa berupa string, array, atau boolean.

Response `201`:

```json
{
  "message": "Data pengunjung berhasil disimpan.",
  "data": { "id": 1, "nama": "Budi Santoso", "nik": "3201...", "rt": "001", "rw": "002", ... }
}
```

## 2. Daftar Pengunjung (Grouped by NIK)

`GET /api/pengunjung/groups`

Response `200`:

```json
{
  "message": "Daftar pengunjung berhasil diambil.",
  "data": [
    {
      "nik": "3201...",
      "nama": "Budi Santoso",
      "alamat": "Jl. Mawar No. 1",
      "rt": "001",
      "rw": "002",
      "total_kunjungan": 3,
      "gds_terbaru": 145.5,
      "gds_trend": "naik",
      "last_input_at": "2026-10-25T10:00:00+00:00"
    }
  ]
}
```

Urutan: dari yang terakhir kali input data (descending `last_input_at`).

## 3. Detail Pengunjung by NIK

`GET /api/pengunjung/detail/{nik}`

Response `200`:

```json
{
  "message": "Detail pengunjung berhasil diambil.",
  "data": {
    "info": {
      "nik": "3201...",
      "nama": "Budi Santoso",
      "alamat": "Jl. Mawar No. 1",
      "rt": "001",
      "rw": "002",
      "gds_terbaru": 145.5,
      "gds_trend": "naik",
      "total_kunjungan": 3
    },
    "chart": {
      "labels": ["24 Okt 2026", "25 Okt 2026"],
      "data": [130.0, 145.5]
    },
    "history": [
      {
        "id": 2,
        "tanggal_kunjungan": "2026-10-25",
        "tanggal_formatted": "25 Okt 2026",
        "gds": 145.5,
        "gejala_klasik": "sering haus",
        "kategori": "prediabetes"
      }
    ]
  }
}
```

## 4. Endpoint lain (tetap tersedia)

- `GET /api/pengunjung` — daftar tanggal kunjungan unik.
- `GET /api/pengunjung/riwayat` — daftar riwayat kunjungan (flat, paginated).
- `GET /api/pengunjung/{date}` — detail pengunjung berdasarkan tanggal.

## Alur Mobile (Riwayat Data)

1. Buka menu "Riwayat Data".
2. Panggil `GET /api/pengunjung/groups` → tampilkan list NIK + nama (urut terbaru input).
3. Saat item diklik, panggil `GET /api/pengunjung/detail/{nik}`.
4. Tampilkan info pengunjung, grafik GDS (dari `chart.labels` & `chart.data`), dan tabel riwayat (dari `history`).
