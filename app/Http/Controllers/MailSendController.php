<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

class MailSendController extends Controller
{
    public function sendmail(){
    	$data = [];
        try {
            Mail::send('mail', $data, function($message){
                $message->to('ponponhoohoo@yahoo.co.jp', 'Test')
                ->subject('This is a test mail');
            });
            echo '送信完了';
        } catch (\Exception $e) {
            dd($e);
        }
    }

}