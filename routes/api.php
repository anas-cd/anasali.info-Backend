<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\ContactController;
use App\Http\Controllers\v1\CourseController;
use App\Http\Controllers\v1\EducationController;
use App\Http\Controllers\v1\ExperienceController;
use App\Http\Controllers\v1\HobbyController;
use App\Http\Controllers\v1\InterestController;
use App\Http\Controllers\v1\LanguageController;
use App\Http\Controllers\v1\ProfileController;
use App\Http\Controllers\v1\ProjectController;
use App\Http\Controllers\v1\SkillController;
use App\Http\Controllers\v1\TechStackController;
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
    /* - experience - */
    Route::get("{major}/experience", [ExperienceController::class, "showByMajor"]);
    /* - course - */
    Route::get("{major}/course", [CourseController::class, "showByMajor"]);
    /* - hobby - */
    Route::get("{major}/hobby", [HobbyController::class, "showByMajor"]);
    /* - project - */
    Route::get("{major}/project", [ProjectController::class, "showByMajor"]);
    /* - skill - */
    Route::get("{major}/skill", [SkillController::class, "showByMajor"]);
    /* - tech - */
    Route::get("{major}/tech", [TechStackController::class, "showByMajor"]);
    /* - language - */
    Route::get("{major}/language", [LanguageController::class, "showByMajor"]);
    /* - interest - */
    Route::get("{major}/interest", [InterestController::class, "showByMajor"]);
    /* - contact - */
    Route::get("contact", [ContactController::class, "showAll"]);

    /* - user - */
    Route::post("user/register", [AuthController::class, "register"]);
    Route::post("user/login", [AuthController::class, "login"]);

    /* - testing route - */
    Route::get("testing", function () {
        echo asset("images/1721270147.JPG");
    });
});

/* -- protected routes -- */
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    /* - profile - */
    Route::patch("{major}/profile", [ProfileController::class, "update"]);
    Route::post("profile", [ProfileController::class, "store"]);
    /* - education - */
    Route::patch("{major}/education", [EducationController::class, "update"]);
    Route::post("education", [EducationController::class, "store"]);
    /* - experience - */
    Route::patch("{major}/experience/{id}", [ExperienceController::class, "update"]);
    Route::post("experience", [ExperienceController::class, "store"]);
    /* - course - */
    Route::patch("{major}/course/{id}", [CourseController::class, "update"]);
    Route::post("course", [CourseController::class, "store"]);
    /* - hobby - */
    Route::patch("{major}/hobby/{id}", [HobbyController::class, "update"]);
    Route::post("hobby", [HobbyController::class, "store"]);
    /* - project - */
    Route::patch("{major}/project/{id}", [ProjectController::class, "update"]);
    Route::post("project", [ProjectController::class, "store"]);
    /* - skill - */
    Route::patch("{major}/skill/{id}", [SkillController::class, "update"]);
    Route::post("skill", [SkillController::class, "store"]);
    /* - tech - */
    Route::patch("{major}/tech/{id}", [TechStackController::class, "update"]);
    Route::post("tech", [TechStackController::class, "store"]);
    /* - language - */
    Route::patch("{major}/language/{id}", [LanguageController::class, "update"]);
    Route::post("language", [LanguageController::class, "store"]);
    /* - interest - */
    Route::patch("{major}/interest/{id}", [InterestController::class, "update"]);
    Route::post("interest", [InterestController::class, "store"]);
    /* - contact - */
    Route::patch("contact/{id}", [ContactController::class, "update"]);
    Route::post("contact", [ContactController::class, "store"]);
    Route::post("contact/mail", [ContactController::class, "sendEmail"]);
});
