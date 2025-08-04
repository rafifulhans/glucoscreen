<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'alamat', 'total_kader', 'total_pengunjung'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kaders()
    {
        return $this->hasMany(Kader::class);
    }

    public function pengunjungs()
    {
        return $this->hasMany(Pengunjung::class);
    }
}
