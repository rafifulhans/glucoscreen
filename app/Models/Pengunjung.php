<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nik',
        'alamat',
        'tanggal_kunjungan',
        'gds',
        'kader_id',
        'posyandu_id'
    ];

    public function kader()
    {
        return $this->belongsTo(Kader::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }
}
