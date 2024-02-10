<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
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
            'reservation_date' => [
                'required',
                'date',
                // 過去の日付を禁止
                'after_or_equal:' . now()->format('Y-m-d'),
            ],
            'reservation_time' => [
                'required',
                // 今日の予約で、現時刻より前の時間を禁止
                Rule::when($this->input('reservation_date') == now()->format('Y-m-d'), 'after_or_equal:' . now()->format('H:i')),
            ],
            'reservation_number' => 'required|integer|min:1|max:20',
        ];
    }

    public function messages()
    {
        return [
            'reservation_date.required' => '日付を入力してください',
            'reservation_date.date' => '日付形式で入力してください',
            'reservation_date.after_or_equal' => '過去の日付で予約することはできません',
            'reservation_time.required' => '時間を入力してください',
            'reservation_time.after_or_equal' => '過去の時間で予約することはできません',
            'reservation_number.required' => '人数を入力してください',
            'reservation_number.integer' => '整数で入力してください',
            'reservation_number.min' => '1人以上の人数を入力してください',
            'reservation_number.max' => '20人以下の人数を入力してください',
        ];
    }
}
