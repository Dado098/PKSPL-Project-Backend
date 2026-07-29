<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnalisisAiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return ['id_proyek' => ['required', 'integer', 'exists:proyek,id_proyek'], 'id_user' => ['required', 'integer', 'exists:users,id_user'], 'pertanyaan' => ['required', 'string'], 'jawaban' => ['required', 'string'], 'sumber_data' => ['nullable', 'string'], 'tipe_analisis' => ['required', Rule::in(['Chat', 'Ringkasan', 'Rekomendasi', 'Prediksi'])]];
    }
}
