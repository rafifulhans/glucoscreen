<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    use HasFactory;

    protected $fillable = ['kategori', 'gejala_klasik', 'gds', 'hasil_pemeriksaan', 'pesan_utama', 'judul', 'isi'];

    public function materis()
    {
        return $this->hasMany(Materi::class)->orderBy('urutan');
    }
}
