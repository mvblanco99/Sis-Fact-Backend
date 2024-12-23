<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacturaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "nota_entrega" => "required",
            "fec_emis" => "required",
            "fec_vcto" => "required",
            "empresa" => "required",
            "total_factura" => "required",
            "tipo" => "required",
            "items_recarga" => "nullable|array", // Permite que items_recarga sea nulo o un array
            "items_recarga.*.codigo_barra" => "required_if:items_recarga,!=,null", // Obligatorio si items_recarga no es nulo
            "items_factura" => "required|array|min:1", // Asegura que sea un array y tenga al menos un elemento
            "items_factura.*.articulo_id" => "required|integer", // Valida que cada item tenga articulo_id como entero
            "items_factura.*.cantidad" => "required|integer", // Valida que cada item tenga cantidad como entero
        ];
    }
}
