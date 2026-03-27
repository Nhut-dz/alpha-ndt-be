<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'post_category_id' => ['required', 'exists:tblpost_category,id'],
            'img' => ['nullable', 'image', 'max:2048'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.max' => 'Tiêu đề tối đa 255 ký tự.',
            'content.required' => 'Nội dung là bắt buộc.',
            'post_category_id.required' => 'Danh mục là bắt buộc.',
            'post_category_id.exists' => 'Danh mục không tồn tại.',
            'img.image' => 'File phải là hình ảnh.',
            'img.max' => 'Hình ảnh tối đa 2MB.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
