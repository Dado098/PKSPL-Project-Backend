<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MetodeValuasiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return ['nama_metode' => [$required, 'string', 'max:150', Rule::unique('metode_valuasi', 'nama_metode')->ignore($this->route('metode_valuasi'), 'id_metode')], 'deskripsi' => ['nullable', 'string'], 'formula' => ['nullable', 'string'], 'parameter' => ['nullable', 'string'], 'status' => [$required, Rule::in(['Aktif', 'Nonaktif'])]];
    }
}
