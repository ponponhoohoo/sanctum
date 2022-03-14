<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;

use App\Http\Controllers\MailSendController;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/mail', [MailSendController::class, 'sendmail']);

// ユーザー登録
Route::post('/register', [RegisterController::class, 'register']);
//ログイン
Route::post('/login', [LoginController::class, 'login']);
//ログアウト
Route::get('/logout', [LoginController::class, 'logout']);

Route::get('/category', [CategoryController::class, 'index']);
//Like
Route::get('/like/{article_id}',[LikeController::class, 'findby_article']);
Route::get('/like/user/{id}/{article_id}',[LikeController::class, 'findby_article_user']);
Route::put('/like/update/{id}/{article_id}',[LikeController::class, 'update']);

Route::prefix('article')->group(function () {
    Route::get('/',[ArticleController::class, 'index']);
    Route::post('search',[ArticleController::class, 'search']);
    Route::get('/{id}',[ArticleController::class, 'show']);
    Route::put('update/{id}',[ArticleController::class, 'update']);
    Route::post('store',[ArticleController::class, 'store']);
    Route::delete('del/{id}',[ArticleController::class, 'destroy']);
});

Route::prefix('comment')->group(function () {
    Route::get('/{id}',[CommentController::class, 'show']);
    Route::post('store/',[CommentController::class, 'store']);
    Route::delete('del/{id}',[CommentController::class, 'destroy']);
});

// Route::post('/password/request', [ForgotPasswordController::class, 'sendResetLinkEmail']); // パスワード再設定メール送信
//ここにパスワードフォームからのリクエストを送信する tokenは、パスワード再設定メール送信のtokenを使用
Route::post('password/reset/', [ResetPasswordController::class, 'reset']);
Route::post('password/request', [ForgotPasswordController::class, 'sendResetLinkEmail']); // パスワード再設定メール送信
Route::get('password/reset/{token}', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');

Route::post('check', [ResetPasswordController::class, 'check']);