<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Memvalidasi data validasi analyst dari request API. */
class ValidasiAnalystRequest extends FormRequest
{
    /** Mengizinkan request karena otorisasi belum diterapkan. */
    public function authorize(): bool { return true; }

    /** Menetapkan aturan validasi hasil valuasi oleh analyst. */
    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return ['id_hasil' => [$required, 'integer', 'exists:hasil_valuasi,id_hasil'], 'id_user' => [$required, 'integer', 'exists:users,id_user'], 'status_validasi' => [$required, Rule::in(['Valid', 'Revisi', 'Ditolak'])], 'metode_analisis' => [$required, Rule::in(['Manual', 'AI'])], 'catatan' => ['nullable', 'string'], 'tanggal_validasi' => [$required, 'date']];
    }
}
