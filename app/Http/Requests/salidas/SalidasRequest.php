<?php

namespace App\Http\Requests\salidas;

use Illuminate\Foundation\Http\FormRequest;

class SalidasRequest extends FormRequest
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
            'cantidad' => 'required|integer',
            'destinatario' => 'required',
            'fecha' => 'required',
            'motivo' => 'required',
            'departamento' => 'required|integer',
            'user' => 'required|integer',
            'articulo_id' => 'required|interger'
        ];
    }
}
