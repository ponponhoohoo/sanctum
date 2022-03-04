<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{any?}', fn() => view('index'))->where('any', '.+');

//Route::get('/mail_index', [MailSendController::class, 'sendmail']);

// Route::get('/signup', fn() => view('index'))->where('any', '.+');
// Route::get('/password-reset', fn() => view('index'))->where('any', '.+');
// Route::get('/login', fn() => view('index'))->where('any', '.+');

// Route::get('/post', fn() => view('index'))->where('any', '.+');

// Route::get('/article', fn() => view('index'))->where('any', '.+');
// Route::get('/detail/{id}', fn() => view('index'))->where('any', '.+');

// Route::get('/article/{any?}', fn() => view('index'))->where('any', '.+');

// Route::get('/reset/input', function () {
//     return view('index');
// })->where('any', '.*');
