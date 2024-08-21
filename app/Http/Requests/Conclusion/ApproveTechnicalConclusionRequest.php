<?php

namespace App\Http\Requests\Conclusion;

use Illuminate\Foundation\Http\FormRequest;

class ApproveTechnicalConclusionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code_1C' => 'nullable',
            'defect_code' => 'required',
            'symptom_code' => 'required',
            'resolution' => 'required|string|max:500',
            'appeal_type' => 'required',
            'conclusion' => 'required|string|max:500',
            'button' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'defect_code.required' => 'Код дефекту є обов\'язковим',
            'defect_code.integer' => 'Код дефекту повинен бути цілим числом',
            'symptom_code.required' => 'Код симптому є обов\'язковим',
            'symptom_code.integer' => 'Код симптому повинен бути цілим числом',
            'resolution.required' => 'Резолюція є обов\'язковою',
            'resolution.string' => 'Резолюція повинна бути рядком',
            'resolution.max' => 'Резолюція не повинна перевищувати 500 символів',
        ];
    }
}
