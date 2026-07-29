<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvisioningServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'id_area' => [$required, 'integer', 'exists:area_terdampak,id_area'],
            'nama_objek' => [$required, 'string', 'max:150'],
            'produktivitas' => [$required, 'numeric', 'decimal:0,4'],
            'harga_pasar' => [$required, 'numeric', 'decimal:0,2'],
            'luas_pemanfaatan' => [$required, 'numeric', 'decimal:0,2'],
            'satuan_luas' => [$required, 'string', 'max:20'],
            'referensi' => ['nullable', 'string'],
            'nilai' => [$required, 'numeric', 'decimal:0,2'],
        ];
    }
}
