<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;


class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    // vueでアクセスするログインへのルート
    public $vueRouteLogin = 'login';
    // vueでアクセスするリセットへのルート
    public $vueRouteReset = 'reset/input';

    public $status = 200;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function resetPassword($token = null,Request $request)
    {
        // if ( app()->isLocal() || app()->runningUnitTests() ) { // .env に APP_ENV=local (ローカル環境) または APP_ENV=testing (テスト環境) と書いてある場合
        //     // テスト環境, ローカル環境用の記述
        //     $baseUrl = "http://127.0.0.1:8000/";
        // }
        // else { // .env に APP_ENV=production (本番環境) などと書いてあった場合
        //     // 本番環境用の記述
        //     $baseUrl = "http://photohamano.herokuapp.com/";
        // }

        $baseUrl = "http://photohamano.herokuapp.com/";
        
        // トークンがあるかチェック
        $isNotFoundResetPassword = PasswordReset::where('email', $request->input('email'))->doesntExist();

        // なかったとき
        if ($isNotFoundResetPassword) {
            // メッセージをクッキーにつけてリダイレクト
            // $route = "{$baseUrl}". $this->vueRouteLogin;
            // return redirect($route);
            abort(403, 'Unauthorized.');

        } else {
            $link = "?token=${token}";
            $route = "{$baseUrl}$this->vueRouteReset{$link}";
        }

        return redirect($route);
    }

    public function check(Request $request) {
        return PasswordReset::where('email', $request->input('email'))->firstOrFail();
    }

    public function reset(ResetRequest $request) {

        $user = User::where('email', $request->input('email'))->firstOrFail();
        $TokenData = PasswordReset::where('email', $request->input('email'))->firstOrFail();
       // $Exsit = PasswordReset::where('email', '=', $request->input('email'))->exists();

        //expire 1時間
        $isPast = Carbon::parse($TokenData->created_at)->addSeconds(3600)->isPast();

        if(Hash::check($request->input('token'), $TokenData->token) && !$isPast){
            $user->forceFill([
                'password' => Hash::make($request->input('password'))
            ])->setRememberToken(Str::random(60));

            $user->save();
            PasswordReset::where('email', $request->input('email'))->delete();

            return response()->json(['success' => true,'status' => $this->status, 'message' => 'パスワードリセットに成功しました。']);
        }else{
            return response()->json(['success' => false,'status' => 400, 'message' => 'パスワードリセットに失敗しました。']);
        }



// echo Hash::make($request->email);
// echo $TokenData->email;
// dd($TokenData);
        // $status = Password::reset(
        //     $request->only('email', 'password', 'token'),
        //     function ($user, $password) {
        //         $user->forceFill([
        //             'password' => Hash::make($password)
        //         ])->setRememberToken(Str::random(60));
    
        //         $user->save();
    
        //         event(new PasswordReset($user));
        //     }
        // );

        // $reset_password_status = Password::reset($credentials, function ($user, $password) {
        //     $user->password = bcrypt($password);
        //     $user->save();
        // });
        
        // if ($reset_password_status == Password::INVALID_TOKEN) {
        //     return ['success' => false]; // トークンが異なる場合の処理
        // }

        //return $status;
    }
}
