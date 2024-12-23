<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cantidad',
        'destinatario_id',
        'fecha',
        'motivo',
        'departamento_id',
        'user_id',
        'unidad_articulo_id'
    ];
}
