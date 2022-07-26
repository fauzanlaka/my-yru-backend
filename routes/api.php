<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EquipmentController;

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

Route::get('login', function(){
    abort(401);
})->name('login');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function(){
    //user
    Route::get('users', [UserController::class, 'index']);
    Route::patch('users/{id}', [UserController::class, 'update']);
    Route::get('userSearch', [UserController::class, 'search']);
    Route::post('userDelete', [UserController::class, 'delete']);
    //admin
    Route::get('student', [StudentController::class, 'index']);
    Route::post('student', [StudentController::class, 'store']);
    Route::patch('student/{id}', [StudentController::class, 'update']);
    Route::post('studentDelete', [StudentController::class, 'delete']);
    //equipment อุปกรณ์
    Route::get('equipment', [EquipmentController::class, 'index']);
    Route::post('equipment', [EquipmentController::class, 'store']);
    Route::patch('equipment/{id}', [EquipmentController::class, 'update']);
    Route::post('equipmentDelete', [EquipmentController::class, 'delete']);

    //borrow
    Route::post('borrow', [BorrowController::class, 'store']);
});
