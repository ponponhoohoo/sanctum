<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Requests\EmailRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(EmailRequest $request)
    {
       // $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        
        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'パスワード再設定メールを送信しました', 'status' => true], 201)
            : response()->json(['message' => 'パスワード再設定メールを送信できませんでした。', 'status' => false], 401);
    }
}