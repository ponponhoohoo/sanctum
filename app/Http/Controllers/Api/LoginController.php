<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use \Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * ログイン
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::whereEmail($request->email)->first();

            $user->tokens()->delete();
            $token = $user->createToken("login:user{$user->id}")->plainTextToken;
      //      dd($token);
            return response()->json(['token' => $token,'user' => $user ], Response::HTTP_OK);
        }

        return response()->json('User Not Found.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * ログアウト
     *
     * @return Response
     */
    public function logout()
    {
        Auth::logout();
        $result = true;
        $status = 200;
        $message = 'ログアウトしました。';
        return response()->json(['result' => $result, 'status' => $status, 'message' => $message]);
    }
}