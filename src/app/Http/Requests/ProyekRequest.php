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
            'luas' => ['nullable', 'numeric', 'decimal:0,2'],
            'satuan_luas' => ['nullable', 'string', 'max:20'],
            'geometry' => ['nullable', 'array'],
            'shp' => ['nullable', 'file', 'max:51200', function (string $attribute, mixed $value, \Closure $fail): void {
                if (strtolower((string) $value->getClientOriginalExtension()) !== 'shp') {
                    $fail('Berkas SHP harus berekstensi .shp.');
                }
            }],
            'shx' => ['nullable', 'file', 'max:51200', function (string $attribute, mixed $value, \Closure $fail): void {
                if (strtolower((string) $value->getClientOriginalExtension()) !== 'shx') {
                    $fail('Berkas SHX harus berekstensi .shx.');
                }
            }],
            'dbf' => ['nullable', 'file', 'max:51200', function (string $attribute, mixed $value, \Closure $fail): void {
                if (strtolower((string) $value->getClientOriginalExtension()) !== 'dbf') {
                    $fail('Berkas DBF harus berekstensi .dbf.');
                }
            }],
            'prj' => ['nullable', 'file', 'max:51200', function (string $attribute, mixed $value, \Closure $fail): void {
                if (strtolower((string) $value->getClientOriginalExtension()) !== 'prj') {
                    $fail('Berkas PRJ harus berekstensi .prj.');
                }
            }],
            'zip' => ['nullable', 'file', 'max:51200', function (string $attribute, mixed $value, \Closure $fail): void {
                if (strtolower((string) $value->getClientOriginalExtension()) !== 'zip') {
                    $fail('Berkas ZIP harus berekstensi .zip.');
                }
            }],
            'tahun' => [$required, 'integer', 'between:1901,2155'],
            'deskripsi' => ['nullable', 'string'],
            'status' => [$required, Rule::in(['Draft', 'Proses', 'Selesai', 'Dibatalkan'])],
        ];
    }
}
