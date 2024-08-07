<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DefectCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.code_1C' => 'required|string|max:40',
            '*.name' => 'required|string|max:200',
            '*.parent_id' => 'nullable',
            '*.is_folder' => 'required|boolean',
            '*.is_deleted' => 'required|boolean',
            '*.created' => 'nullable|date',
            '*.edited' => 'nullable|date',
        ];
    }
}
