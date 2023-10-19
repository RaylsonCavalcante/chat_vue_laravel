<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\JWTAuthentication;

/* ======================================================================================================== */

/* Login */
Route::get('/', [LoginController::class, 'loginScreen'])->name('loginScreen');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware([JWTAuthentication::class])->group(function(){
    Route::post('/checkToken', [LoginController::class, 'checkToken'])->name('checkToken');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/* ======================================================================================================== */

/* Register */
Route::post('/register', [RegisterController::class, 'register'])->name('register');

/* ======================================================================================================== */

/* Home */
Route::get('/showUsers', [RegisterController::class, 'showUsers'])->name('showUsers');

// //Messages
Route::post('/showMessages', [MessageController::class, 'showMessages'])->name('showMessages');
Route::post('/sendMessage', [MessageController::class, 'sendMessage'])->name('sendMessage');

// //Verification
Route::get('/newMessage', [MessageController::class, 'newMessage'])->name('newMessage');
Route::post('/updateRefresh', [MessageController::class, 'updateRefresh'])->name('updateRefresh');
Route::post('/readMessage', [MessageController::class, 'readMessage'])->name('readMessage');
// /* ======================================================================================================== */

// /* Profile */
Route::post('/dataUser', [RegisterController::class, 'dataUser'])->name('dataUser');
Route::post('/updateUser', [RegisterController::class, 'updateUser'])->name('updateUser');
