<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleEntrada extends Model
{
    protected $table = 'detalle_entradas';
    public $timestamps = false;
    protected $fillable = ['cantidad', 'precio_unitario', 'entradas_id', 'productos_id'];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
    ];

    public function entrada()
    {
        return $this->belongsTo(Entrada::class, 'entradas_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productos_id');
    }
}
