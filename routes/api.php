<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GatewayController;
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

Route::get("/students/{nim}/courses", [
    StudentController::class,
    "coursesByStudent",
]);
Route::get("/students/{nim}", [StudentController::class, "show"]);

Route::put("/students/{nim}", [StudentController::class, "update"]);
Route::patch("/students/{nim}", [StudentController::class, "update"]);
Route::delete("/students/{nim}", [StudentController::class, "destroy"]);

// JWT
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::middleware(["dummy.jwt"])->group(function () {

    Route::get("/profile", [AuthController::class, "profile"]);
    Route::get("/token-check", [AuthController::class, "tokenCheck"]);

    // admin only
    Route::get("/admin/dashboard", function () {
        return response()->json([
            "message" => "Welcome to Admin Dashboard"
        ]);
    })->middleware("role:admin");

    // user only
    Route::get("/user/dashboard", function () {
        return response()->json([
            "message" => "Welcome to User Dashboard"
        ]);
    })->middleware("role:user");

    // manager only
    Route::get("/manager/dashboard", function () {
        return response()->json([
            "message" => "Welcome to Manager Dashboard"
        ]);
    })->middleware("role:manager");

    Route::post("/logout", [AuthController::class, "logout"]);
});

// API Gateway
Route::middleware(["dummy.jwt"])->prefix("gateway")->group(function () {

    // admin & user
    Route::get("/students", [GatewayController::class, "getStudents"])
        ->middleware("role:admin,user");

    // admin only
    Route::post("/students", [GatewayController::class, "createStudent"])
        ->middleware("role:admin");

    Route::put("/students/{nim}", [GatewayController::class, "updateStudent"])
        ->middleware("role:admin");

    Route::patch("/students/{nim}", [GatewayController::class, "updateStudent"])
        ->middleware("role:admin");

    Route::delete("/students/{nim}", [GatewayController::class, "deleteStudent"])
        ->middleware("role:admin");
});
