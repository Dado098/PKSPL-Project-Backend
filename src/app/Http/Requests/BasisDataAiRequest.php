<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BasisDataAiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return ['nama_basis' => [$required, 'string', 'max:150'], 'versi' => [$required, 'string', 'max:100'], 'deskripsi' => ['nullable', 'string'], 'path_file' => [$required, 'string', 'max:255'], 'status' => [$required, Rule::in(['Aktif', 'Nonaktif'])], 'jenis_basis' => ['nullable', 'string', 'max:50'], 'model_embedding' => ['nullable', 'string', 'max:100'], 'jumlah_dokumen' => [$required, 'integer', 'min:0']];
    }
}
