<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('/webhook',[\App\Http\Controllers\TelegramController::class,'webhook']);
Route::get('/chat',[\App\Http\Controllers\ChatGPTController::class,'askToChatGpt']);
Route::get("/user",[\App\Http\Controllers\TelegramController::class,'user']);
