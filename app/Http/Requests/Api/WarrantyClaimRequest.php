<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WarrantyClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.id' => 'nullable|integer',
            '*.code_1C' => 'required|string|max:255',
            '*.number' => 'nullable|integer',
            '*.number_1c' => 'nullable',
            '*.status_1c' => 'nullable',
            '*.product_name' => 'nullable|string|max:255',
            '*.product_article' => 'nullable|string|max:255',
            '*.factory_number' => 'nullable|string|max:255',
            '*.barcode' => 'nullable|string|max:255',
            '*.service_partner' => 'nullable|integer',
            '*.service_contract' => 'nullable|integer',
            '*.point_of_sale' => 'nullable|string',
            '*.autor' => 'nullable|integer',
            '*.date' => 'nullable|date',
            '*.date_of_sale' => 'nullable|date',
            '*.date_of_claim' => 'nullable|date',
            '*.type_of_claim' => 'nullable|string|max:255',
            '*.is_deleted' => 'boolean',
            '*.product_group_id' => 'nullable|integer|',
            '*.file_paths' => 'nullable|string',
            '*.comment' => 'nullable|string|max:500',
            '*.comment_service' => 'nullable|string|max:500',
            '*.comment_part' => 'nullable|string|max:500',
            '*.sender_name' => 'nullable|string|max:255',
            '*.sender_phone' => 'nullable|string|max:50',
            '*.receipt_number' => 'nullable|string|max:50',
            '*.details' => 'nullable|string',
            '*.deteails_reason' => 'nullable|string|max:500',
            '*.status' => 'nullable|string|max:255',
            '*.spare_parts' => 'nullable|array',
            '*.spare_parts.*.line_number' => 'nullable|integer',
            '*.spare_parts.*.spareparts_articul' => 'nullable|integer',
            '*.spare_parts.*.qty' => 'nullable|integer',
            '*.spare_parts.*.price' => 'nullable|numeric',
            '*.spare_parts.*.discount' => 'nullable|numeric',
            '*.spare_parts.*.sum' => 'nullable|numeric',
            '*.files' => 'nullable|array',
            '*.files.*.path' => 'nullable|string|max:255',
            '*.files.*.filename' => 'nullable|string|max:255',
            '*.service_works' => 'nullable|array',
            '*.service_works.*.code_1c' => 'nullable|string|max:40',
            '*.service_works.*.line_number' => 'nullable|integer',
            '*.service_works.*.qty' => 'nullable|decimal:0,2',
            '*.service_works.*.price' => 'nullable|numeric',
            '*.service_works.*.discount' => 'nullable|numeric',
            '*.service_works.*.sum' => 'nullable|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
            ], 422)
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ])
        );
    }
}
