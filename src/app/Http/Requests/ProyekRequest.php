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
            'id_user' => ['nullable'],
            'kode_proyek' => ['nullable', 'string', 'max:50'],
            'id_provinsi' => ['nullable', 'integer', 'exists:provinsi,id_provinsi'],
            'id_kabupaten_kota' => ['nullable', 'integer', 'exists:kabupaten_kota,id_kabupaten_kota'],
            'id_kecamatan' => ['nullable', 'integer', 'exists:kecamatan,id_kecamatan'],
            'id_desa_kelurahan' => ['nullable', 'integer', 'exists:desa_kelurahan,id_desa_kelurahan'],
            'nama_proyek' => [$required, 'string', 'max:150'],
            'tujuan_valuasi' => ['nullable', 'string'],
            'alamat_lengkap' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'luas' => ['nullable', 'numeric'],
            'satuan_luas' => ['nullable', 'string', 'max:20'],
            'geometry' => ['nullable'],
            'shp' => ['nullable', function (string $attribute, mixed $value, \Closure $fail): void {
                $files = is_array($value) ? $value : [$value];
                foreach ($files as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        if (strtolower($file->getClientOriginalExtension()) !== 'shp') {
                            $fail('Berkas SHP harus berekstensi .shp.');
                        }
                    }
                }
            }],
            'shx' => ['nullable', function (string $attribute, mixed $value, \Closure $fail): void {
                $files = is_array($value) ? $value : [$value];
                foreach ($files as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        if (strtolower($file->getClientOriginalExtension()) !== 'shx') {
                            $fail('Berkas SHX harus berekstensi .shx.');
                        }
                    }
                }
            }],
            'dbf' => ['nullable', function (string $attribute, mixed $value, \Closure $fail): void {
                $files = is_array($value) ? $value : [$value];
                foreach ($files as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        if (strtolower($file->getClientOriginalExtension()) !== 'dbf') {
                            $fail('Berkas DBF harus berekstensi .dbf.');
                        }
                    }
                }
            }],
            'prj' => ['nullable', function (string $attribute, mixed $value, \Closure $fail): void {
                $files = is_array($value) ? $value : [$value];
                foreach ($files as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        if (strtolower($file->getClientOriginalExtension()) !== 'prj') {
                            $fail('Berkas PRJ harus berekstensi .prj.');
                        }
                    }
                }
            }],
            'zip' => ['nullable', function (string $attribute, mixed $value, \Closure $fail): void {
                $files = is_array($value) ? $value : [$value];
                foreach ($files as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        if (strtolower($file->getClientOriginalExtension()) !== 'zip') {
                            $fail('Berkas ZIP harus berekstensi .zip.');
                        }
                    }
                }
            }],
            'shapefile_files' => ['nullable'],
            'shapefile_files.*' => ['nullable', function (string $attribute, mixed $value, \Closure $fail): void {
                if ($value instanceof \Illuminate\Http\UploadedFile) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (! in_array($ext, ['shp', 'shx', 'dbf', 'prj', 'zip'])) {
                        $fail('Berkas shapefile harus berekstensi .shp, .shx, .dbf, .prj, atau .zip.');
                    }
                }
            }],
            'tahun' => ['nullable', 'integer'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['Draft', 'Proses', 'Selesai', 'Dibatalkan'])],
        ];
    }
}
