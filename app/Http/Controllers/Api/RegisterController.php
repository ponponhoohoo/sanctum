<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
//use App\Http\Requests\UserCreateRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;

class RegisterController extends Controller
{
    
    public function register(Request $request)
    {
        /** @var Illuminate\Validation\Validator $validator */
        $message = [
            'name.required' => '名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスの形式を正しく入力してください。',
            'email.unique' => 'すでに登録されているメールアドレスです',
            'password.required' => 'パスワードを入力してください',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ],$message);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $User = new User();

        $user = $User->create([
            'name' =>  $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Mail::to($user->email)->send(new RegisterMail($user->name,$user->email));
        
        return response()->json('User registration completed', Response::HTTP_OK);

    }
}