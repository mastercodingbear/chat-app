<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatGroupController;
use App\Http\Controllers\MessageController;

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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/list-groups', [ChatGroupController::class, 'listGroups']);
    Route::post('/create-group', [ChatGroupController::class, 'create']);
    Route::post('/join-group/{groupId}', [ChatGroupController::class, 'join']);
    Route::post('/leave-group/{groupId}', [ChatGroupController::class, 'leave']);
    Route::post('/send/{groupId}', [MessageController::class, 'send']);
    Route::get('/list/{groupId}', [MessageController::class, 'list']);
});
