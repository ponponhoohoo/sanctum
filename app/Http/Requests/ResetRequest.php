<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetRequest extends FormRequest
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
            'email' => ['email','required','exists:users'],
            'password' => 'required|min:6',
            'confirmed' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => ':attributeの形式を正しく入力してください。',
            'email.required' => ':attributeは必須項目です。',
            'email.exists' => ':attributeが見つかりません。',
            'password.required' => ':attributeは必須項目です。',
            'password.min' => ':attributeは6文字以上です。',
            'confirmed.required' => ':attributeを入力してください。',
            'confirmed.same' => ':attributeが一致しません。',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'confirmed' => '確認用パスワード',
        ];
    }

     /**
     * [override] バリデーション失敗時ハンドリング
     *
     * @param Validator $validator
     * @throw HttpResponseException
     * @see FormRequest::failedValidation()
     */
    protected function failedValidation(Validator $validator) {
        $response['status']  = 400;
        $response['statusText'] = '認証に失敗しました。';
        $response['errors']  = $validator->errors();
        throw new HttpResponseException(
            response()->json( $response, 200 )
        );
    }
}
