<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $text,$description,$password;
    public function __construct($text,$link,$password)
    {
        $this->text = $text;
        $this->description = $link;
        $this->password = $password;
    }

    public function build()
    {
        $data = [
            'subject' => 'Artoorsdesign',
            'text' => $this->text,
            'description' => $this->description,
            'password' => $this->password,
        ];
        return $this->markdown('emails.send_mail',compact('data'))->subject('Artoorsdesign ');
    }
}
