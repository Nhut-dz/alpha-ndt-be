<?php

namespace App\Http\Requests\PostCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Tên danh mục tối đa 255 ký tự.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
