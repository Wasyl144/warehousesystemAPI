<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Permissions\PermissionController;
use App\Http\Controllers\Permissions\RoleController;

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

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/self',function (Request $request) {
        return auth()->user();
    });

    Route::get('/permissions/self', [PermissionController::class, 'self'])->name('permission.self');

    Route::resource('/user', UserController::class )->except(['index']);
    Route::get('/users', [UserController::class, 'index'])->name('user.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permission.index');

    Route::apiResource('/role', RoleController::class);
    Route::apiResource('/items', \App\Http\Controllers\ItemController::class);
    Route::apiResource('/categories', \App\Http\Controllers\CategoryController::class);
    Route::get('/role/{id}/permissions', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::put('/role/{id}/permissions', [PermissionController::class, 'update'])->name('permission.update');
    Route::get('/generate/qr', [\App\Http\Controllers\QRController::class, 'generateQr']);
    Route::get('/roles', [RoleController::class, 'getAllRoles']);

});
