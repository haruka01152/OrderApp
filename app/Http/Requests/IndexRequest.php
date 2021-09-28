<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'customer_name' => ['required', 'max:30'],
            'construction_name' => ['required', 'max:100'],
            'orders.*.*' => ['nullable', 'max:20'],
        ];
    }

    public function messages()
    {
        return [
            'customer_name.required' => 'お客様名を入力してください。',
            'customer_name.max' => 'お客様名は30文字以内で入力してください。',
            'construction_name.required' => '案件名を入力してください。', 
            'construction_name.max' => '案件名は100文字以内で入力してください。',
            'orders.*.memo.max' => '備考は20文字以内で入力してください。', 
        ];
    }
}
