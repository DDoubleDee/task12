<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MachineController;

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

Route::post('/login', [UserController::class, 'login']);
Route::post('/images/{id}', [MachineController::class, 'image']);
Route::delete('/logout', [UserController::class, 'logout'])->middleware('utoken');
Route::get('/{type}', [MachineController::class, 'get'])->middleware('utoken');
Route::get('/search/{type}', [MachineController::class, 'search'])->middleware('utoken');
Route::post('/machines', [MachineController::class, 'create'])->middleware('utoken');
Route::put('/machines/{id}', [MachineController::class, 'update'])->middleware('utoken');
Route::delete('/machines/{id}', [MachineController::class, 'delete'])->middleware('utoken');
Route::post('/verify-compatibility', [MachineController::class, 'check'])->middleware('utoken');