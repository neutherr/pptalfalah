<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Statis (CMS Pages)
Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('page.show');
// Alias menu navigasi utama agar tidak error jika dipanggil via route() helper
Route::get('/profil',    [PageController::class, 'show'])->defaults('slug', 'profil')->name('profil');
Route::get('/fasilitas', [\App\Http\Controllers\FacilityController::class, 'index'])->name('fasilitas');

// Program Unggulan
Route::prefix('program')->name('programs.')->group(function () {
    Route::get('/',        [ProgramController::class, 'index'])->name('index');
    Route::get('/{slug}',  [ProgramController::class, 'show'])->name('show');
});

// PPDB
Route::get('/ppdb', [PpdbController::class, 'index'])->name('ppdb');

// Galeri
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery');

// Berita & Artikel
Route::prefix('berita')->name('articles.')->group(function () {
    Route::get('/',        [ArticleController::class, 'index'])->name('index');
    Route::get('/{slug}',  [ArticleController::class, 'show'])->name('show');
});

// Agenda
Route::prefix('agenda')->name('agendas.')->group(function () {
    Route::get('/',        [AgendaController::class, 'index'])->name('index');
    Route::get('/{slug}',  [AgendaController::class, 'show'])->name('show');
});

// Pengumuman
Route::prefix('pengumuman')->name('announcements.')->group(function () {
    Route::get('/',        [AnnouncementController::class, 'index'])->name('index');
    Route::get('/{slug}',  [AnnouncementController::class, 'show'])->name('show');
});

// Kontak
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');

// Download File (Brosur, dll)
Route::get('/unduh/{file}', [DownloadController::class, 'download'])->name('download');
