<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateRestaurantRecord extends FormRequest
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
            'name' => 'required|string|max:50',
            'area' => 'required|string|in:東京都,大阪府,福岡県',
            'genre' => 'required|string|in:寿司,焼肉,イタリアン,居酒屋,ラーメン',
            'overview' => 'required|string|max:400',
            'image' => 'required|url|ends_with:.jpeg,.jpg,.png',
        ];
    }
}
