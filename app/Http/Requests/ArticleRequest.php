<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleRequest extends FormRequest
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
            'title' => 'required|max:20',
            'content' => 'required',
            'category' => 'required|numeric',
            'path'=>['required_without:path_original','file','mimes:jpeg,png,jpg,bmb','max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => ':attributeは必須項目です。',
            'title.max' => ':attributeは20文字以内です。',
            'content.required' => ':attributeは必須項目です。',
            'category.required' => ':attributeは必須項目です。',
            'category.numeric' => ':attributeは数値です。',
            'path.required' => ':attributeは必須項目です。',
            'path.required_without' => ':attributeは必須項目です。',
            'path.max' => ':attributeは2048MG以内です',
            'path.file' => ':attributeをアップロードしてください',
            'path.mimes' => ':attributeはjpeg,png,jpg,bmbのみ有効です',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'content' => 'コンテンツ',
            'category' => 'カテゴリ',
            'path' => '画像',
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
