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
            'orders.*' => ['nullable', 'max:20'],
            'images.*' => ['mimes:jpeg,jpg,png,gif,pdf', 'max:10240'],
            'alert_config' => ['nullable', 'required_without:notAlert'],
            'notAlert' => ['nullable', 'required_without:alert_config'],
            'remarks' => ['nullable', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'customer_name.required' => 'お客様名を入力してください。',
            'customer_name.max' => 'お客様名は30文字以内で入力してください。',
            'construction_name.required' => '案件名を入力してください。', 
            'construction_name.max' => '案件名は100文字以内で入力してください。',
            'orders.*.max' => '備考は20文字以内で入力してください。', 
            'alert_config.required_without' => 'アラート設定を入力してください。',
            'notAlert.required_without' => 'アラート設定を入力してください。',
            'remarks.max' => '案件・発注備考は1000文字以内で入力してください。' 
        ];
    }
}
