<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartamentoResource extends JsonResource
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
            'descripcion' => $this->descripcion
            // 'departamento' => new DepartamentoResource($this->departamento), // Si también tienes un recurso para Departamento
            // 'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
