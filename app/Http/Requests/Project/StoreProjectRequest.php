<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'client' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:10'],
            'duration' => ['nullable', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:100'],
            'img' => ['nullable', 'image', 'max:5120'],
            'highlights' => ['nullable', 'array'],
            'highlights.*' => ['string'],
            'methods' => ['nullable', 'array'],
            'methods.*' => ['string'],
            'standards' => ['nullable', 'array'],
            'standards.*' => ['string'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tên dự án là bắt buộc.',
            'title.max' => 'Tên dự án tối đa 255 ký tự.',
            'img.image' => 'File phải là hình ảnh.',
            'img.max' => 'Hình ảnh tối đa 5MB.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'highlights.array' => 'Điểm nổi bật phải là mảng.',
            'methods.array' => 'Phương pháp NDT phải là mảng.',
            'standards.array' => 'Tiêu chuẩn phải là mảng.',
        ];
    }
}
