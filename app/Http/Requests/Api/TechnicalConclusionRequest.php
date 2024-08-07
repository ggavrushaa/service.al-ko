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
            '*.id' => 'nullable|integer',
            '*.warranty_claim_id' => 'required|integer',
            '*.defect_code' => 'nullable|integer',
            '*.symptom_code' => 'nullable|integer',
            '*.conclusion' => 'required|string|max:500',
            '*.resolution' => 'required|string|max:500',
            '*.date' => 'required|date',
            '*.appeal_type' => 'nullable|string|max:255',
            '*.is_deleted' => 'nullable|boolean',
        ];
    }
}
