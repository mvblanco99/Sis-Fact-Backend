<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion',
        'categoria_id',
    ];


    //Relacion de uno a muchos con unidad_articulo
    public function unidad_articulo()
    {
        return $this->hasMany(UnidadArticulo::class);
    }
}
