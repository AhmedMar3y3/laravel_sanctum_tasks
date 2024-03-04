<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



//public routes
Route::post('/register',[AuthController::class , 'register']);
Route::post('/login',[AuthController::class , 'login']);
//private routes
Route::group(['middleware' => ['auth:sanctum']], function()
{
    Route::resource('/tasks', TaskController::class);
    Route::post('/logout',[AuthController::class , 'logout']); 
    
});
