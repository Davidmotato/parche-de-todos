<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    public $timestamps = false;
    protected $fillable = ['nombre', 'telefono', 'direccion'];

    public function entradas()
    {
        return $this->hasMany(Entrada::class, 'proveedores_id');
    }
}
