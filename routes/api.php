<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/ping", function () {
    return response()->json(["message" => "pong"]);
});

Route::get("/status", function () {
    return response()->json([
        "app" => "Todo API",
        "status" => "running",
    ]);
});

// CRUD Student
Route::post("/students", [StudentController::class, "store"]);
Route::get("/students", [StudentController::class, "index"]);
Route::get("/students/search", [StudentController::class, "search"]);

Route::get("/students/{nim}/mata-kuliah", [
    StudentController::class,
    "mataKuliahByStudent",
]);
Route::get("/students/{nim}", [StudentController::class, "show"]);

Route::put("/students/{nim}", [StudentController::class, "update"]);
Route::patch("/students/{nim}", [StudentController::class, "update"]);
Route::delete("/students/{nim}", [StudentController::class, "destroy"]);

// auth
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
Route::middleware(["dummy.jwt"])->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/profile", [AuthController::class, "profile"]);
    Route::get("/token-check", [AuthController::class, "tokenCheck"]);
});
