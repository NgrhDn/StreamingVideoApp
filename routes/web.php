<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Ini adalah rute utama aplikasi Laravel untuk bagian admin Lifemedia.
| Semua rute admin diamankan dengan middleware `auth`.
|--------------------------------------------------------------------------
*/

// --- Halaman Utama (Landing Page Default Laravel) ---

// Halaman utama
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/tentang', function () {
    return view('tentang_kami');
});
Route::get('/kategori/{id}', [FrontendController::class, 'show'])->name('kategori.show');


// --- Rute Autentikasi (Login & Logout) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/multimedia/ajax', [AdminController::class, 'ajaxMultimedia'])->name('multimedia.ajax');
Route::get('/kategori/{id}', [FrontendController::class, 'show'])->name('kategori.show');


// --- Rute ADMIN (dilindungi oleh middleware 'auth') ---
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::prefix('admin')->group(function () {
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    });

    Route::post('/pengaturan', [PengaturanController::class, 'store'])->name('pengaturan.store');
    Route::get('/pengaturan', [PengaturanController::class, 'index']);
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::get('/pengaturan/{id}/edit', [PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::put('/pengaturan/{id}', [PengaturanController::class, 'update'])->name('pengaturan.update');
    Route::delete('/pengaturan/{id}', [PengaturanController::class, 'destroy'])->name('pengaturan.destroy');
    Route::get('/pengaturan/create', [PengaturanController::class, 'create'])->name('pengaturan.create');


    // Multimedia (CRUD & Helper AJAX)
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');

    // Multimedia (CRUD & Helper AJAX)
    Route::prefix('multimedia')->name('multimedia.')->group(function () {
        Route::get('/', [AdminController::class, 'multimedia'])->name('index');
        Route::post('/', [AdminController::class, 'storeMultimedia'])->name('store');
        Route::post('/{id}/toggle-status', [AdminController::class, 'toggleMultimediaStatus'])->name('toggleStatus');
        Route::post('/delete-selected', [AdminController::class, 'deleteSelectedMultimedia'])->name('deleteSelected');
    });
    
    Route::post('/multimedia/check-unique', [AdminController::class, 'checkJudulDanLink'])->name('multimedia.checkJudulDanLink');
    Route::post('/multimedia/{id}/update', [AdminController::class, 'updateMultimedia'])->name('multimedia.update');
    
    // Kategori (CRUD & Toggle)
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::post('/', [KategoriController::class, 'store'])->name('store');
        Route::post('/{id}/toggle', [KategoriController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/{id}/update', [KategoriController::class, 'update'])->name('update');
        Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('destroy');
    });


});
