<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['nama', 'username', 'password', 'role'];

    protected $hidden = ['password'];

    public function kader()
    {
        return $this->hasOne(Kader::class, 'user_id');
    }
}
