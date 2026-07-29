<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportingServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return ['id_area' => [$required, 'integer', 'exists:area_terdampak,id_area'], 'fungsi_pendukung' => [$required, 'string', 'max:150'], 'deskripsi' => ['nullable', 'string'], 'referensi' => ['nullable', 'string'], 'nilai' => [$required, 'numeric', 'decimal:0,2']];
    }
}
