<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $FromEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name,string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ponponhoohoo@yahoo.co.jp') // 送信元
        ->subject('会員登録完了のお知らせ') // メールタイトル
        ->view('body') // どのテンプレートを呼び出すか
        ->with(['name' => $this->name,'email' => $this->email]);
    }
}
