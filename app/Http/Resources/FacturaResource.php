<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacturaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nota_entrega' => $this->nota_entrega,
            'fec_emis' => $this->fec_emis,
            'fec_vcto' => $this->fec_vcto,
            'procesada' => $this->procesada == 1 ? 'Procesada' : 'No procesada',
            'total_factura' => $this->total_factura,
            'empresa' => $this->empresa,
            'user' => new UserResource($this->user),
        ];
    }
}
