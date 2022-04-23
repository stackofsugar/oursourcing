<?php

use Illuminate\Support\Facades\Route;

/**
 * >>> User-defined Controller Includes <<<
 * \AuthController    => Handling all auth operations
 * \UnauthController  => Handling all common pages (for non-login users)
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnauthController;
use App\Http\Controllers\TestController;

/**
 * >>> Regular Routes <<<
 * > Contains:
 * root (/)
 */

Route::controller(UnauthController::class)->group(function () {
    Route::get("/", "index")->name("home");
});

/**
 * >>> Auth Routes <<<
 * > Contains:
 * register (/register) => GET (view) and POST (model)
 * login (/login)       => GET (view) and POST (model)
 * logout (/logout)     => POST (model)
 * 
 * > Middlewares:
 * auth        => (default auth middleware) for any users logged in, acts as "pasien"
 * admin       => make changes to reservations, can assign "pasien" to poli 
 * admin-poli  => add "tindakan", "obat", and "bhp"
 * cashier     => access cost per visit
 */

Route::controller(AuthController::class)->group(function () {
    // Auth GET views
    Route::get("/login", "viewLogin")
        ->name("login")
        ->middleware("guest");
    Route::get("/register", "viewRegister")
        ->name("register")
        ->middleware("guest");
    // Auth POST to models
    Route::post("/login", "authenticate")
        ->middleware("guest");
    Route::post("/register", "store")
        ->middleware("guest");
    // Special Auth get to logout (destroy session)
    Route::get("/logout", "destroy")
        ->name("logout")
        ->middleware("auth");
});

/**
 * Test Routes
 */

Route::controller(TestController::class)->group(function () {
});
