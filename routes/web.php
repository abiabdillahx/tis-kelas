<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/hello', function(){
    return 'Hello Laravel!';
});

Route::get('/about', function () {
    return "Nama: Abi Abdillah, NIM: 245150701111027";
});

Route::get('/home', [HomeController::class, 'index']);


// LK REST dan SOAP (brone)
Route::get('/hello', function () {
    return "Hello Laravel!";
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/home', [HomeController::class, 'index']);