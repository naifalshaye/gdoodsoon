<?php

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contact', [MailController::class, 'submitContactForm'])->name('contact.submit');
Route::post('/subscribe', [MailController::class, 'subscribe'])->name('newsletter.subscribe');
