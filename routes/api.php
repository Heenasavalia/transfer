<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileTransferController;
use App\Http\Controllers\VoyageController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('newmail', [UserController::class, 'newmail']);

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', [UserController::class, 'getAuthenticatedUser']); // Login User Details
    Route::post('change-password', [UserController::class, 'ChangePassword']); // User Change PAssword
    Route::post('forgot-password', [FileTransferController::class, 'forgotPassword']);

    Route::post('create-transfer', [VoyageController::class, 'CreateTransferData']);
});

