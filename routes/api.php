<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegistrationController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::group(['middleware' => ['auth:sanctum']], function () {
//     // Super Admin Routes
//     Route::group([], base_path('routes/sub-routes/super-admin/super-admin-routes.php'));

//     // Buyer Routes
//     Route::group([], base_path('routes/sub-routes/buyer/buyer-routes.php'));

//     // Funder Routes
//     Route::group([], base_path('routes/sub-routes/funder/funder-routes.php'));

//     // Supplier Routes
//     Route::group([], base_path('routes/sub-routes/supplier/supplier-routes.php'));
// });
