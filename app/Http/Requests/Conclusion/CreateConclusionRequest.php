<?php

namespace App\Http\Requests\Conclusion;

use Illuminate\Foundation\Http\FormRequest;

class CreateConclusionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code_1C' => 'required|string',
            'number' => 'required|string',
            'parent_doc' => 'required|exists:warranty_claims,id',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:255',
            'product_article' => 'required|string|max:50',
            'factory_number' => 'required|string|max:50',
            'product_name' => 'required|string|max:200',
            'barcode' => 'required|string|max:20',
            'service_partner' => 'required|exists:alko_db.user_partners,id',
            'service_contract' => 'required|exists:alko_db.contracts,id',
            'date_of_sale' => 'required|date',
            'point_of_sale' => 'required|exists:alko_db.user_partners,id',
            'date_of_claim' => 'required|date',
            'details' => 'required|string|max:500',
            'type_of_claim' => 'required|string',
            'defect_code' => 'nullable|exists:defect_codes,id',
            'symptom_code' => 'nullable|exists:symptom_codes,id',
            'resolution' => 'required|string|max:500',
            'autor' => 'required|exists:alko_db.users,id',
            'date' => 'required|date',
        ];
    }
}
