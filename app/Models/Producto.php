<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = [
        'nombre', 'precio_compra', 'precio_venta', 'stock', 'stock_minimo', 'estado', 'categorias_id',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' =>  'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categorias_id');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'productos_id');
    }

    public function detalleEntradas()
    {
        return $this->hasMany(DetalleEntrada::class, 'productos_id');
    }
}
