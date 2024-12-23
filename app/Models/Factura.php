<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    public const PROCESADA = 1;
    public const NOPROCESADA  = 2;
    public const RECARGA = 2;
    public const UNICA = 1;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nota_entrega',
        'fec_emis',
        'fec_vcto',
        'empresa',
        'total_factura',
        'user_id',
        'procesada'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
