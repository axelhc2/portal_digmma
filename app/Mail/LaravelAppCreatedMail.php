<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LaravelAppCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $app;
    public $url;
    public $firstName;
    public $lastName;
    public $domain;

    public function __construct($app, $url)
    {
        $this->app = $app;
        $this->url = $url;
        $this->firstName = $app->first_name;
        $this->lastName = $app->last_name;
        $this->domain = $app->domain;
    }

    public function build()
    {
        return $this->subject('Votre projet Laravel a démarré !')
            ->view('emails.laravel_app_created');
    }
} 