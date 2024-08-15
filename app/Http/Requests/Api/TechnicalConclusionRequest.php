<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TechnicalConclusionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.code_1C' => 'nullable|string|max:255',
            '*.number_1c' => 'required|string|max:255',
            '*.status_1c' => 'required|boolean',
            '*.id' => 'nullable|integer',
            '*.warranty_claim_id' => 'required|string',
            '*.defect_code' => 'nullable|string',
            '*.symptom_code' => 'nullable|string',
            '*.conclusion' => 'nullable|string|max:500',
            '*.resolution' => 'nullable|string|max:500',
            '*.date' => 'required|date',
            '*.appeal_type' => 'nullable|string|max:255',
            '*.is_deleted' => 'nullable|boolean',
        ];
    }
}
