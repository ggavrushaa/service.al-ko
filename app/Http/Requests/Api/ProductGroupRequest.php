<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProductGroupRequest extends FormRequest
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
        ];
    }
}
