<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    protected $table = 'entradas';
    public $timestamps = false;
    protected $fillable = ['fecha', 'total', 'proveedores_id', 'usuarios_id'];

    protected $casts = [
        'fecha' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedores_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuarios_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleEntrada::class, 'entradas_id');
    }
}
