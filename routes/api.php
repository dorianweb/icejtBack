<?php

use App\Http\Controllers\Api\ClassicCreamController;
use App\Http\Controllers\Api\CustomCreamController;
use App\Http\Controllers\Api\FlavorController;
use App\Http\Controllers\Api\SupplementController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\CustomCream;
use App\Models\Flavor;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/auth_user', function (Request $request) {
    return $request->user();
});

Route::apiResource('flavors', FlavorController::class);
Route::apiResource('classic_creams', ClassicCreamController::class);
Route::apiResource('custom_creams', CustomCreamController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('supplements', SupplementController::class);
