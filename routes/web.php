<?php

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contact', [MailController::class, 'submitContactForm'])->name('contact.submit');
Route::post('/subscribe', [MailController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/test', function () {
   dd(env('DB_HOST'),env('DB_USERNAME'),env('DB_DATABASE'));
});
Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
});
