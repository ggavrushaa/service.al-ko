<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;

class WarrantyClaimRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        Log::info('Request Data for Validation:', $this->all());
        return [
            'number' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'autor' => 'nullable|integer',
            'service_partner' => 'nullable|integer',
            'service_contract' => 'nullable|integer',
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:15',
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:25',
            'product_article' => 'nullable|string|max:255',
            'product_name' => 'nullable|string|max:255',
            'factory_number' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'point_of_sale' => 'nullable|string|max:255',
            'date_of_sale' => 'nullable|date',
            'date_of_claim' => 'nullable|date',
            'receipt_number' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'deteails_reason' => 'nullable|string',
            'comment' => 'nullable|string',
            'comment_service' => 'nullable|string',
            'comment_part' => 'nullable|string',
            'product_group_id' => 'nullable|integer',
            'service_works' => 'nullable|array',
            'service_works.*' => 'string',
            'hours' => 'nullable|array',
            'hours.*' => 'nullable|numeric|',
            'file[]' => 'nullable',
            'file.*' => 'nullable',
            'files[]' => 'nullable',
            'files.*' => 'nullable',
            'spare_parts' => 'nullable|array',
            'spare_parts.*.spare_parts' => 'nullable|string|max:255',
            'spare_parts.*.name' => 'nullable|string|max:255',
            'spare_parts.*.qty' => 'nullable|integer',
            'spare_parts.*.price' => 'nullable|numeric',
            'spare_parts.*.sum' => 'nullable|numeric',
            'buttons' => 'nullable',
        ];
    }
}
