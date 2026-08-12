<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Kader extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['nama', 'user_id', 'pemimpin_user_id'];

    protected $hidden = ['password'];

    public function pemimpin()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengunjungs()
    {
        return $this->hasMany(Pengunjung::class);
    }
}
