<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\EducationController;
use App\Http\Controllers\v1\ProfileController;
use Illuminate\Support\Facades\Route;

/* --- v1 Routes (personal use only)--- */

/* -- public routes -- */
Route::prefix('v1')->group(function () {
    /* - full resume - */
    // Route::get("{major}", []); // TODO: configure after developing all sections
    /* - profile - */
    Route::get("{major}/profile", [ProfileController::class, "showByMajor"]);
    /* - education - */
    Route::get("{major}/education", [EducationController::class, "showByMajor"]);
    /* - user - */
    Route::post("user/register", [AuthController::class, "register"]);
    Route::post("user/login", [AuthController::class, "login"]);
});

/* -- protected routes -- */
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    /* - profile - */
    Route::patch("{major}/profile", [ProfileController::class, "update"]);
    Route::post("profile", [ProfileController::class, "store"]);
    /* - education - */
    Route::patch("{major}/education", [EducationController::class, "update"]);
    Route::post("education", [EducationController::class, "store"]);
});
