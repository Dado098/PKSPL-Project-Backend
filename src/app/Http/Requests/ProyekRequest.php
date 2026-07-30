<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi payload proyek, termasuk referensi wilayah yang dinormalisasi.
 */
class ProyekRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'id_user' => [$required, 'integer', 'exists:users,id_user'],
            'id_provinsi' => [$required, 'integer', 'exists:provinsi,id_provinsi'],
            'id_kabupaten_kota' => [$required, 'integer', 'exists:kabupaten_kota,id_kabupaten_kota'],
            'id_kecamatan' => [$required, 'integer', 'exists:kecamatan,id_kecamatan'],
            'id_desa_kelurahan' => [$required, 'integer', 'exists:desa_kelurahan,id_desa_kelurahan'],
            'nama_proyek' => [$required, 'string', 'max:150'],
            'tujuan_valuasi' => [$required, 'string'],
            'alamat_lengkap' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'decimal:0,6'],
            'longitude' => ['nullable', 'numeric', 'decimal:0,6'],
            'tahun' => [$required, 'integer', 'between:1901,2155'],
            'deskripsi' => ['nullable', 'string'],
            'status' => [$required, Rule::in(['Draft', 'Proses', 'Selesai', 'Dibatalkan'])],
        ];
    }
}
