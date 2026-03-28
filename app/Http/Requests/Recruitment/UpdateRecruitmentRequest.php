<?php

namespace App\Http\Requests\Recruitment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecruitmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'position' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'in:full-time,part-time,contract,intern'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'salary_range' => ['nullable', 'string', 'max:255'],
            'requirements' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'img' => ['nullable', 'image', 'max:5120'],
            'status' => ['sometimes', 'integer', 'in:0,1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Tiêu đề tối đa 255 ký tự.',
            'employment_type.in' => 'Loại hình không hợp lệ.',
            'contact_email.email' => 'Email liên hệ không hợp lệ.',
            'img.image' => 'File phải là hình ảnh.',
            'img.max' => 'Hình ảnh tối đa 5MB.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
