<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
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

Route::post('/auth/social-login/{provider}', [LoginController::class, 'handleProviderCallback']);
Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/verify-code', [LoginController::class, 'verifyCode']);
Route::post('/auth/renew-code', [LoginController::class, 'renewCode']);
Route::post('/auth/register/', [LoginController::class, 'register']);
Route::post('/auth/forgot-password', [LoginController::class, 'forgotPassword']);
Route::get('/auth/validate_token/{token}', [LoginController::class, 'validateToken']);
Route::post('/auth/reset-password', [LoginController::class, 'resetPassword']);

Route::apiResource('user', UserController::class)->middleware('auth:api');;
