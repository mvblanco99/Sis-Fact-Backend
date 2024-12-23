<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadArticulo extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'articulo_id',
        'estado',
        'factura_id',
        'codigo_barra'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }   
}
