<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\WriteOffController;
use App\Http\Middleware\JsonFormat;


Route::prefix('v1')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->middleware(JsonFormat::class);
    //Route::post('/login', [UserController::class, 'login'])->middleware(JsonFormat::class);
    Route::post('/login', [UserController::class, 'login'])->name('login')->middleware(JsonFormat::class);
    Route::post('/refresh', [UserController::class, 'refreshToken']);
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api');

    Route::middleware(['auth:api'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware(JsonFormat::class);
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware(JsonFormat::class);

    Route::get('/items', [ItemController::class, 'index']);
    Route::post('/items', [ItemController::class, 'store'])->middleware(JsonFormat::class);
    Route::put('/items/{item}', [ItemController::class, 'update'])->middleware(JsonFormat::class);

    //Route::post('/items/import', [ImportController::class, 'import'])->middleware(JsonFormat::class);;
    Route::post('/items/import/preview', [ImportController::class, 'preview'])->middleware(JsonFormat::class);
    Route::post('/items/import/confirm', [ImportController::class, 'confirm'])->middleware(JsonFormat::class);


    Route::post('/items/writeoff/preview', [WriteOffController::class, 'preview'])->middleware(JsonFormat::class);;
    Route::post('/items/writeoff/confirm', [WriteOffController::class, 'confirm'])->middleware(JsonFormat::class);; // Galutiniam veiksmui




    });
});
