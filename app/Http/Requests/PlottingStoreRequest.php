<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlottingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'dosen_id' => ['required', 'integer', 'exists:dosens,id'],
            'matakuliah_id' => ['required', 'integer', 'exists:matakuliahs,id'],
            'kelas' => ['required', 'string'],
            'semester' => ['required', 'string'],
            'tahun' => ['required', 'integer'],
        ];
    }
}
