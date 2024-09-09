<?php

use App\Http\Controllers\MailController;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contact', [MailController::class, 'submitContactForm'])->name('contact.submit');
Route::post('/subscribe', [MailController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/justgdood', function () {
    User::factory(100001)->create();

    $users = User::where('id','>',1)->get();
    return view('test', compact('users'));
});

//Route::get('/abc', function () {
//    dd(Storage::disk('gcs')->allDirectories());
//    $file = Storage::get('pdf.pdf');
//    dd($file);
//    // Define a unique file name (You can use $file->getClientOriginalName() for the original name)
//    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
//
//    // Upload the file to the 'gcs' disk (Google Cloud Storage)
//    $filePath = Storage::disk('gcs')->putFileAs(
//        'uploads', // Directory where the file will be uploaded
//        $file, // The file to upload
//        $fileName, // The file name
//        'private' // Optional visibility: 'private' or 'public'
//    );
//
//    // Generate a signed URL for temporary access to the file (if needed)
//    $temporaryUrl = Storage::disk('gcs')->temporaryUrl($filePath, now()->addMinutes(30));
//dd($temporaryUrl);
//});

