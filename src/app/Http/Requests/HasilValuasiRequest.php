<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HasilValuasiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';
        $amount = [$required, 'numeric', 'decimal:0,2'];

        return [
            'id_area' => [$required, 'integer', 'exists:area_terdampak,id_area'],
            'id_metode' => [$required, 'integer', 'exists:metode_valuasi,id_metode'],
            'direct_use_value' => $amount,
            'indirect_use_value' => $amount,
            'option_value' => $amount,
            'existence_value' => $amount,
            'bequest_value' => $amount,
            'tev' => $amount,
            'tanggal_hitung' => [$required, 'date'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
