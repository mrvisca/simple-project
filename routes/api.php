<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('otentikasi')->group(function () {
    Route::post("masuk", [LoginController::class, 'login']);
    Route::post("daftar", [LoginController::class, 'register']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('produk')->group(function () {
        Route::get("list", [ProductController::class, 'list']);
        Route::post("tambah-baru", [ProductController::class, 'add_product']);
        Route::get("detail/{id}", [ProductController::class, 'detail']);
        Route::put("update-data/{id}", [ProductController::class, 'update']);
        Route::delete("hapus-data/{id}", [ProductController::class, 'delete']);
    });

    Route::prefix('kategori')->group(function () {
        Route::get("list", [CategoryController::class, 'index']);
        Route::post("tambah-baru", [CategoryController::class, 'tambah']);
        Route::put("update-data/{id}", [CategoryController::class, 'update']);
        Route::delete("hapus-data/{id}", [CategoryController::class, 'delete']);
    });

    Route::prefix('pelanggan')->group(function () {
        Route::get("list", [ClientController::class, 'list']);
        Route::post("tambah-baru", [ClientController::class, 'tambah']);
        Route::put("update-data/{id}", [ClientController::class, 'update']);
        Route::delete("hapus-data/{id}", [ClientController::class, 'delete']);
    });

    Route::prefix('transaksi')->group(function () {
        Route::get("list-produk", [TransactionController::class, 'produk_pos']);
        Route::post("buat-transaksi", [TransactionController::class, 'transaksi']);
        Route::get("list-penjualan", [TransactionController::class, 'data_penjualan']);
        Route::get("list-openbill", [TransactionController::class, 'list_open_bill']);
    });
});
