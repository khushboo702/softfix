<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/registration', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('post-listing', [PostController::class, 'postList']);

// --------------------- After Login all Api ---------------
Route::middleware(['mycustom'])->group(function () {
    Route::post('save-post', [PostController::class, 'store']);
    Route::delete('delete-post', [PostController::class, 'destroy']);
    Route::post('update-post', [PostController::class, 'update']);
    Route::get('users-post', [UserController::class, 'userPost']);

});
