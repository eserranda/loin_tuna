<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'getAllUserTest']);
Route::get('/users/{id}', [UserController::class, 'findOneUser']);



Route::get('/test', function () {
    return  response()->json(['message' => 'hello world'], 200);
});
