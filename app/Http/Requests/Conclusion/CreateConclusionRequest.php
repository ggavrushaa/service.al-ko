<?php

namespace App\Http\Requests\Conclusion;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'date' => 'required|date_format:d.m.Y',
            'autor' => 'required',
            'parent_doc' => 'required',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:255',
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',

            'product_article' => 'required|string|max:50',
            'product_name' => 'required|string|max:200',
            'factory_number' => 'required|string|max:50',
            'barcode' => 'required|string|max:20',
            'point_of_sale' => 'required|exists:mysql.user_partners,id', 
            'date_of_sale' => 'required|date',
            'receipt_number' => 'required|string|max:50',
            'date_of_claim' => 'required|date',
            'details' => 'required|string|max:500',
            'deteails_reason' => 'nullable|string|max:500',
            'type_of_claim' => 'nullable|string',
            'comment' => 'string|max:500',
            'file' => 'nullable|array',
            'file.*' => 'file|mimes:jpg,jpeg,png|max:5120',
            'product_group_id' => 'required',
            'comment_service' => 'nullable|string|max:500',
            'spare_parts' => 'nullable|array',
            'spare_parts.*' => 'required|exists:second_db.warranty_claim_spareparts,id',
            'comment_part' => 'nullable|string|max:500',
            'status' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'code_1C.required' => 'Поле "Код 1С" є обов\'язковим.',
            'number.required' => 'Поле "Номер" є обов\'язковим.',
            'date.required' => 'Поле "Дата" є обов\'язковим.',
            'autor.required' => 'Поле "Автор" є обов\'язковим.',
            'autor.exists' => 'Вибраний автор недійсний.',
            
            'client_name.required' => 'Поле "Ім\'я клієнта" є обов\'язковим.',
            'client_phone.required' => 'Поле "Телефон клієнта" є обов\'язковим.',
            'sender_name.required' => 'Поле "Ім\'я відправника" є обов\'язковим.',
            'sender_phone.required' => 'Поле "Телефон відправника" є обов\'язковим.',
            'sender_phone.regex' => 'Телефон відправника повинен бути дійсним номером телефону.',

            'product_article.required' => 'Поле "Артикул продукту" є обов\'язковим.',
            'product_name.required' => 'Поле "Назва продукту" є обов\'язковим.',
            'factory_number.required' => 'Поле "Заводський номер" є обов\'язковим.',
            'barcode.required' => 'Поле "Штрихкод" є обов\'язковим.',
            'point_of_sale.required' => 'Поле "Місце продажу" є обов\'язковим.',
            'point_of_sale.exists' => 'Вибране місце продажу недійсне.', 
            'date_of_sale.required' => 'Поле "Дата продажу" є обов\'язковим.',
            'receipt_number.required' => 'Поле "Номер квитанції" є обов\'язковим.',
            'date_of_claim.required' => 'Поле "Дата звернення" є обов\'язковим.',
            'details.required' => 'Поле "Деталі" є обов\'язковим.',
            'deteails_reason.required' => 'Поле "Причина дефекту" є обов\'язковим.',
            'type_of_claim.required' => 'Поле "Тип претензії" є обов\'язковим.',
            'comment.string' => 'Поле "Коментар" має бути рядком.',
            'file.required' => 'Поле "Файл" є обов\'язковим.',
            'file.*.file' => 'Кожен файл повинен бути дійсним файлом.',
            'file.*.mimes' => 'Кожен файл повинен бути формату: jpg, jpeg, png.',
            'file.*.max' => 'Кожен файл не повинен перевищувати 5120 кілобайт.',
            'product_group_id.required' => 'Поле "Група товарів" є обов\'язковим.',
            'product_group_id.exists' => 'Вибрана група товарів недійсна.',
            'comment_service.string' => 'Поле "Коментар до сервісу" має бути рядком.',
            'status.array' => 'Поле "Статус" має бути масивом.',
            'status.*.exists' => 'Вибраний статус недійсний.',
            'spare_parts.array' => 'Поле "Запчастини" має бути масивом.',
            'spare_parts.*.required' => 'Поле "Запчастина" є обов\'язковим.',
            'spare_parts.*.integer' => 'Поле "Запчастина" має бути цілим числом.',
            'spare_parts.*.exists' => 'Вибрана запчастина недійсна.',
            'comment_part.string' => 'Поле "Коментар до запчастини" має бути рядком.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Log::error('Validation failed', $validator->errors()->toArray());
    }

}
