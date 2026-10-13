<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Override;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $fillable = ['nombre', 'email', 'password', 'estado', 'roles_id'];
    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];

    #[Override]
    public function getRememberTokenName()
    {
        return '';
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'roles_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'usuarios_id');
    }

    public function entradas()
    {
        return $this->hasMany(Entrada::class, 'usuarios_id');
    }
}
