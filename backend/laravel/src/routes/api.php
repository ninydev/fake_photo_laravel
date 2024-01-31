<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FakeImage\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// При написании кода программист формирует набор маршрутов для работы с одной
// или несколькими взаимосвязанными сущностями - максимально независимыми от других
// Например - User + UserGroup + Jwt - сервис авторизации и аутентификации

// Такой тип может даже навредить работоспособности проекта
Route::post('auth/register',[AuthController::class,'register']);
Route::post('auth/login', [AuthController::class,'login']);
Route::post('auth/refresh', [AuthController::class,'refresh']);
Route::post('auth/logout', [AuthController::class,'logout']);


Route::post('fake_images/upload', [UploadController::class,'upload']);
