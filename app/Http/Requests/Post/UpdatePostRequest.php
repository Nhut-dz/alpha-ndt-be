<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'string'],
            'post_category_id' => ['sometimes', 'exists:tblposts_category,id'],
            'img' => ['nullable', 'image', 'max:2048'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Tiêu đề tối đa 255 ký tự.',
            'post_category_id.exists' => 'Danh mục không tồn tại.',
            'img.image' => 'File phải là hình ảnh.',
            'img.max' => 'Hình ảnh tối đa 2MB.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
