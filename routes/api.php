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
use App\Http\Controllers\BillOfLadingController;
use App\Http\Controllers\DepartmentController;
use App\Http\Middleware\JsonFormat;
use App\Http\Kernel;
use App\Http\Middleware\PermissionMiddleware;

Route::prefix('v1')->group(function () {
    //Route::post('/register', [UserController::class, 'register'])->middleware(JsonFormat::class);
    Route::post('/login', [UserController::class, 'login'])->name('login')->middleware(JsonFormat::class);
    Route::post('/refresh', [UserController::class, 'refreshToken']);
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api');
    Route::get('/me', [UserController::class, 'me'])->middleware('auth:api');;


    Route::middleware(['auth:api'])->group(function () {

        //Naudotojų valdymas
        //Route::get('/users', [UserController::class, 'index'])->middleware( JsonFormat::class);
        Route::get('/users', [UserController::class, 'index'])->middleware(['json', 'permission:view-user']);
        Route::get('/users/{user}', [UserController::class, 'show'])->middleware(['json', 'permission:manage-users']);
        Route::put('/users/{user}', [UserController::class, 'update'])->middleware(['json', 'permission:manage-users']);
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware(['json', 'permission:manage-users']);
        Route::post('/users', [UserController::class, 'store'])->middleware(['json', 'permission:manage-user']);

        //Inventorius
        Route::get('/items', [ItemController::class, 'index'])->middleware(['json', 'permission:view-inventory']);
        Route::post('/items', [ItemController::class, 'store'])->middleware(['json', 'permission:add-item']);
        Route::put('/items/{item}', [ItemController::class, 'update'])->middleware(['json', 'permission:add-item']);

        //Importas
        Route::post('/items/import/preview', [ImportController::class, 'preview'])->middleware(['json', 'permission:import-items']);
        Route::post('/items/import/confirm', [ImportController::class, 'confirm'])->middleware(['json', 'permission:import-items']);

        // Nurašymas
        Route::post('/items/writeoff/preview', [WriteOffController::class, 'preview'])->middleware(['json', 'permission:writeoff-items']);
        Route::post('/items/writeoff/confirm', [WriteOffController::class, 'confirm'])->middleware(['json', 'permission:writeoff-items']);
        Route::get('/writeoff-logs', [WriteOffController::class, 'logs'])->middleware(['json', 'permission:writeoff-items']);


        // Rolių ir leidimų peržiūra
        Route::get('/roles', [RoleController::class, 'index'])->middleware(['json', 'permission:manage-users']);
        Route::get('/permissions', [PremissionController::class, 'index'])->middleware(['json', 'permission:manage-users']);

        Route::post('/user-roles', [UserRoleController::class, 'store'])->middleware(['json', 'permission:manage-users']);
        Route::put('/user-roles/{userRole}', [UserRoleController::class, 'update'])->middleware(['json', 'permission:manage-users']);
        Route::delete('/user-roles/{userRole}', [UserRoleController::class, 'destroy'])->middleware(['json', 'permission:manage-users']);
        Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->middleware(['json', 'permission:manage-users']);
        Route::get('/roles/{role}', [RoleController::class, 'show'])->middleware(['json', 'permission:manage-users']);



        // Užsakymų kūrimas
        Route::post('/orders/full', [OrderController::class, 'createFullOrder'])->middleware(['json', 'permission:create-order']);

        // Užsakymų sąrašas, tipai
        Route::get('/orders', [OrderController::class, 'index'])->middleware(['json', 'permission:view-inventory']);
        Route::get('/ordertypes', [OrderTypeController::class, 'index'])->middleware(['json', 'permission:create-order']);

        // Užsakymų patvirtinimas / atmetimas
        Route::post('/orders/{order}/approve', [OrderController::class, 'approve'])->middleware(['json', 'permission:approve-order']);
        Route::post('/orders/{order}/reject', [OrderController::class, 'reject'])->middleware(['json', 'permission:approve-order']);

        // Užsakymų istorija
        Route::get('/orderhistory', [OrderHistoryController::class, 'index'])->middleware(['json', 'permission:view-inventory']);

        // Laikino išdavimo žurnalas
        Route::get('/temporary-issues', [OrderController::class, 'userTemporaryIssues'])->middleware(['json', 'permission:view-inventory']);
        Route::post('/temporary-issues/{id}/return', [OrderController::class, 'returnIssuedItem'])->middleware(['json', 'permission:create-order']);

        //Route::get('/billoflading/{id}/download', [BillOfLadingController::class, 'generateBillOfLadingPdf'])->middleware(['json', 'premission:billoflading']);
        //Route::get('/billoflading/pdf/{billId}', [BillOfLadingController::class, 'generateBillOfLadingPdf'])->middleware(['json', 'premission:billoflading']);
        Route::get('/billoflading/{id}/download', [BillOfLadingController::class, 'generateBillOfLadingPdf'])->middleware(['json', 'permission:billoflading']);
        Route::get('/billoflading/pdf/{billId}', [BillOfLadingController::class, 'generateBillOfLadingPdf'])->middleware(['json', 'permission:billoflading'])->name('billoflading.download');;
        Route::post('/billoflading/create', [BillOfLadingController::class, 'store'])->middleware(['json', 'permission:billoflading']);



        Route::get('/department', [DepartmentController::class, 'index'])->middleware(['json', 'permission:view-departments']);



    });
});
