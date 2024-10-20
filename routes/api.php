<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/customers', [CustomerController::class, 'storeApi']);
Route::get('/customers', [CustomerController::class, 'indexApi']);
Route::get('/customers/{customer}', [CustomerController::class, 'showApi']);
Route::get('/customers/{customer}', [CustomerController::class, 'editApi']);
Route::put('/customers/{customer}', [CustomerController::class, 'updateApi']);
Route::delete('/customers/{customer}', [CustomerController::class, 'destroyApi']);

Route::get('/projects', [ProjectController::class, 'indexApi']);
Route::post('/projects', [ProjectController::class, 'storeApi']);
Route::get('/projects/{project}', [ProjectController::class, 'showApi']);
Route::get('/projects/{project}/edit', [ProjectController::class, 'editApi']);
Route::put('/projects/{project}', [ProjectController::class, 'updateApi']);
Route::delete('/projects/{project}', [ProjectController::class, 'destroyApi']);
