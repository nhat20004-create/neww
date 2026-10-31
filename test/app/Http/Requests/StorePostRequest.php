<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(Post::CATEGORIES)],
            'content' => ['required', 'string', 'min:10'],
            'image_file' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'category.required' => 'Vui lòng chọn chủ đề.',
            'category.in' => 'Chủ đề không hợp lệ.',
            'content.required' => 'Vui lòng nhập nội dung bài viết.',
            'content.min' => 'Nội dung phải có ít nhất 10 ký tự.',
            'image_file.required' => 'Vui lòng chọn ảnh bài viết.',
            'image_file.uploaded' => 'Tải ảnh thất bại, ảnh có thể vượt quá dung lượng cho phép (2MB).',
            'image_file.image' => 'File tải lên phải là hình ảnh.',
            'image_file.mimes' => 'Ảnh chỉ chấp nhận định dạng jpeg, jpg, png, gif, webp.',
            'image_file.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
        ];
    }
}
