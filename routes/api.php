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
use App\Http\Controllers\Inventory\InventoryImportController;

// Nauji inventory controlleriai
use App\Http\Controllers\Inventory\InventoryItemController;
use App\Http\Controllers\Inventory\ItemVariantController;
use App\Http\Controllers\Inventory\StockBatchController;
use App\Http\Controllers\Inventory\AssetUnitController;
use App\Http\Controllers\Inventory\InventoryMovementController;
use App\Http\Controllers\Inventory\InventoryIssueController;

Route::prefix('v1')->group(function () {
    //Route::post('/register', [UserController::class, 'register'])->middleware(JsonFormat::class);
    Route::post('/login', [UserController::class, 'login'])->name('login')->middleware(JsonFormat::class);
    Route::post('/refresh', [UserController::class, 'refreshToken']);
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api');
    Route::get('/me', [UserController::class, 'me'])->middleware('auth:api');

    Route::middleware(['auth:api'])->group(function () {

        // Naudotojų valdymas
        Route::get('/users', [UserController::class, 'index'])->middleware(['json', 'permission:view-user']);
        Route::get('/users/{user}', [UserController::class, 'show'])->middleware(['json', 'permission:manage-users']);
        Route::put('/users/{user}', [UserController::class, 'update'])->middleware(['json', 'permission:manage-users']);
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware(['json', 'permission:manage-users']);
        Route::post('/users', [UserController::class, 'store'])->middleware(['json', 'permission:manage-user']);

        // Inventorius (sena sistema)
        Route::get('/items', [ItemController::class, 'index'])->middleware(['json', 'permission:view-inventory']);
        Route::post('/items', [ItemController::class, 'store'])->middleware(['json', 'permission:add-item']);
        Route::put('/items/{item}', [ItemController::class, 'update'])->middleware(['json', 'permission:add-item']);

        // Importas (sena sistema)
        Route::post('/items/import/preview', [ImportController::class, 'preview'])->middleware(['json', 'permission:import-items']);
        Route::post('/items/import/confirm', [ImportController::class, 'confirm'])->middleware(['json', 'permission:import-items']);

        // Nurašymas (sena sistema)
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

        // Važtaraščiai
        Route::get('/billoflading/{id}/download', [BillOfLadingController::class, 'generateBillOfLadingPdf'])->middleware(['json', 'permission:billoflading']);
        Route::get('/billoflading/pdf/{billId}', [BillOfLadingController::class, 'generateBillOfLadingPdf'])->middleware(['json', 'permission:billoflading'])->name('billoflading.download');
        Route::post('/billoflading/create', [BillOfLadingController::class, 'store'])->middleware(['json', 'permission:billoflading']);

        Route::get('/department', [DepartmentController::class, 'index'])->middleware(['json', 'permission:view-departments']);
    });
});

/*
|--------------------------------------------------------------------------
| Nauja inventory sistema (v2)
|--------------------------------------------------------------------------
|
| Nauja schema veikia greta senos. Kol kas neliečiam senų v1 route'ų.
|
*/

Route::prefix('v2')->middleware(['auth:api'])->group(function () {

    Route::prefix('inventory')->group(function () {

        // Nauji inventory items
        Route::get('/items', [InventoryItemController::class, 'index'])->middleware(['json', 'permission:view-inventory']);
        Route::post('/items', [InventoryItemController::class, 'store'])->middleware(['json', 'permission:add-item']);
        Route::get('/items/{inventory_item}', [InventoryItemController::class, 'show'])->middleware(['json', 'permission:view-inventory']);
        Route::put('/items/{inventory_item}', [InventoryItemController::class, 'update'])->middleware(['json', 'permission:add-item']);
        Route::delete('/items/{inventory_item}', [InventoryItemController::class, 'destroy'])->middleware(['json', 'permission:add-item']);

        // Variantai
        Route::get('/variants', [ItemVariantController::class, 'index'])->middleware(['json', 'permission:view-inventory']);
        Route::post('/variants', [ItemVariantController::class, 'store'])->middleware(['json', 'permission:add-item']);
        Route::get('/variants/{item_variant}', [ItemVariantController::class, 'show'])->middleware(['json', 'permission:view-inventory']);
        Route::put('/variants/{item_variant}', [ItemVariantController::class, 'update'])->middleware(['json', 'permission:add-item']);
        Route::delete('/variants/{item_variant}', [ItemVariantController::class, 'destroy'])->middleware(['json', 'permission:add-item']);

        // Partijos
        Route::get('/batches', [StockBatchController::class, 'index'])->middleware(['json', 'permission:view-inventory']);
        Route::post('/batches', [StockBatchController::class, 'store'])->middleware(['json', 'permission:add-item']);
        Route::get('/batches/{stock_batch}', [StockBatchController::class, 'show'])->middleware(['json', 'permission:view-inventory']);
        Route::put('/batches/{stock_batch}', [StockBatchController::class, 'update'])->middleware(['json', 'permission:add-item']);
        Route::delete('/batches/{stock_batch}', [StockBatchController::class, 'destroy'])->middleware(['json', 'permission:add-item']);

        // Vienetiniai daiktai / turtas
        Route::get('/asset-units', [AssetUnitController::class, 'index'])->middleware(['json', 'permission:view-inventory']);
        Route::post('/asset-units', [AssetUnitController::class, 'store'])->middleware(['json', 'permission:add-item']);
        Route::get('/asset-units/{asset_unit}', [AssetUnitController::class, 'show'])->middleware(['json', 'permission:view-inventory']);
        Route::put('/asset-units/{asset_unit}', [AssetUnitController::class, 'update'])->middleware(['json', 'permission:add-item']);
        Route::delete('/asset-units/{asset_unit}', [AssetUnitController::class, 'destroy'])->middleware(['json', 'permission:add-item']);

        // Judėjimų istorija
        Route::get('/movements', [InventoryMovementController::class, 'index'])->middleware(['json', 'permission:view-inventory']);
        Route::post('/movements', [InventoryMovementController::class, 'store'])->middleware(['json', 'permission:add-item']);
        Route::get('/movements/{inventory_movement}', [InventoryMovementController::class, 'show'])->middleware(['json', 'permission:view-inventory']);

        Route::post('/import/preview', [InventoryImportController::class, 'preview'])->middleware(['json', 'permission:import-items']);
        Route::post('/import/confirm', [InventoryImportController::class, 'confirm'])->middleware(['json', 'permission:import-items']);
        Route::post('/issue', [InventoryIssueController::class, 'store'])->middleware(['json', 'permission:create-order']);
    });
});
