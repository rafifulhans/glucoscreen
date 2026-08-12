<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = ['informasi_id', 'urutan', 'isi'];

    public function informasi()
    {
        return $this->belongsTo(Informasi::class);
    }
}
