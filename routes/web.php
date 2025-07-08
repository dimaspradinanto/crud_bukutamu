<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntrianGuestController;
use App\Http\Controllers\AntrianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// ✅ Route untuk tamu (guest)
Route::get('/', [AntrianGuestController::class, 'form'])->name('antrian.form');
Route::post('/ambil-antrian', [AntrianGuestController::class, 'ambil'])->name('antrian.ambil');

// ✅ Route untuk admin & staff
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AntrianController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    Route::post('/antrian/reset', [AntrianController::class, 'reset'])->name('antrian.reset');
    Route::post('/antrian/{id}/panggil', [AntrianController::class, 'panggil'])->name('antrian.panggil');
});

require __DIR__ . '/auth.php';
