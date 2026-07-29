<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegulatingServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'id_area' => [$required, 'integer', 'exists:area_terdampak,id_area'],
            'jenis_regulating' => [$required, 'string', 'max:150'],
            'indikator' => [$required, 'string', 'max:150'],
            'satuan' => [$required, 'string', 'max:50'],
            'nilai_indikator' => [$required, 'numeric', 'decimal:0,4'],
            'referensi' => ['nullable', 'string'],
            'nilai' => [$required, 'numeric', 'decimal:0,2'],
        ];
    }
}
