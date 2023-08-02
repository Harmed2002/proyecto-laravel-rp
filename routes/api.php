<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Agrupamos por la ruta
Route::prefix("v1/auth")->group(function() {

    Route::post("login", [AuthController::class, "funIngresar"]);
    Route::post("register", [AuthController::class, "funRegistro"]);

    // Agrupamos para proteger por middleware
    Route::middleware('auth:sanctum')->group(function() {

        Route::get("profile", [AuthController::class, "funPerfil"]);
        Route::post("logout", [AuthController::class, "funSalir"]);
    });

});

// Protejo para autenticación con Sanctum
Route::middleware('auth:sanctum')->group(function() {
    // Users
    Route::apiResource("users", UserController::class);
    Route::apiResource("permiso", PermisoController::class); // Deberia ser an plural: permisos
    Route::apiResource("role", RoleController::class);
});
