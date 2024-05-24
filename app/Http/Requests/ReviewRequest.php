<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'required',
            'review' => 'required|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'rating.required' => '評価は必須です。',
            'review.required' => '口コミは必須です。',
            'review.string' => '口コミは文字列で入力してください。',
            'review.max' => '口コミは400文字以内で入力してください。',
            'image.image' => '画像ファイルをアップロードしてください。',
            'image.mimes' => '画像ファイルの形式はJPEGまたはPNGのみアップロードできます。',
        ];
    }
}
