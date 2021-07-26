<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileTransferController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('reset-password/{email}/{token}', [FileTransferController::class,'resetPassword']);

Route::post('reset-password-submit',[FileTransferController::class,'resetPasswordSubmit']);


Route::get('mail-verification/{email}/{token}', [UserController::class,'VerifyEmail']);
