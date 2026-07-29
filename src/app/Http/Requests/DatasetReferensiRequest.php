<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DatasetReferensiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return ['nama_dataset' => [$required, 'string', 'max:150'], 'kategori' => [$required, 'string', 'max:100'], 'tahun' => ['nullable', 'integer', 'between:1901,2155'], 'file_dataset' => [$required, 'string', 'max:255'], 'tipe_file' => ['nullable', 'string', 'max:20'], 'sumber' => [$required, 'string', 'max:255'], 'deskripsi' => ['nullable', 'string'], 'status' => [$required, Rule::in(['Aktif', 'Nonaktif'])]];
    }
}
