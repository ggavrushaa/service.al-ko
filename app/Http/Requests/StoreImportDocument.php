<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImportDocument extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'file_path' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:2048',
            'category_id' => 'nullable',
            'doc_type_id' => 'nullable',
            'added' => 'nullable',
        ];
    }
}
