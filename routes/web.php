<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {

    Mail::raw('ده إيميل تجريبي من LoopTech 🚀', function ($message) {
        $message->to('Kareemmedo432@gmail.com')
                ->subject('Test Email');
    });

    return 'Email Sent Successfully!';
});
