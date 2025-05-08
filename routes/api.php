<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\WriteOffController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PremissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTypeController;
use App\Http\Controllers\OrderHistoryController;
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
    Route::post('/users', [UserController::class, 'store'])->middleware(JsonFormat::class);


        Route::get('/items', [ItemController::class, 'index']);
    Route::post('/items', [ItemController::class, 'store'])->middleware(JsonFormat::class);
    Route::put('/items/{item}', [ItemController::class, 'update'])->middleware(JsonFormat::class);

    //Route::post('/items/import', [ImportController::class, 'import'])->middleware(JsonFormat::class);;
    Route::post('/items/import/preview', [ImportController::class, 'preview'])->middleware(JsonFormat::class);
    Route::post('/items/import/confirm', [ImportController::class, 'confirm'])->middleware(JsonFormat::class);


    Route::post('/items/writeoff/preview', [WriteOffController::class, 'preview'])->middleware(JsonFormat::class);;
    Route::post('/items/writeoff/confirm', [WriteOffController::class, 'confirm'])->middleware(JsonFormat::class);; // Galutiniam veiksmui

        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/permissions', [PremissionController::class, 'index']);

        Route::post('/user-roles', [UserRoleController::class, 'store']);
        Route::put('/user-roles/{userRole}', [UserRoleController::class, 'update']);
        Route::delete('/user-roles/{userRole}', [UserRoleController::class, 'destroy']);

        Route::post('/orders/full', [OrderController::class, 'createFullOrder'])->middleware([JsonFormat::class]);
        Route::get('/orders', [OrderController::class, 'index'])->middleware([JsonFormat::class]);
        Route::get('/ordertypes', [OrderTypeController::class, 'index'])->middleware([JsonFormat::class]);

        Route::get('/orderhistory', [OrderHistoryController::class, 'index'])->middleware([JsonFormat::class]);

    });
});
