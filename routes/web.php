<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StagiaireController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('stagiaire')->group(function () {
    Route::get('/{id}', [StagiaireController::class, 'index'])->name('stagiaire.index');
    Route::get('/edit/{id}', [StagiaireController::class, 'edit'])->name('stagiaire.edit');
    Route::put('/{stagiaire}', [StagiaireController::class, 'update'])->name('stagiaire.update');
});

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/add', [AdminController::class, 'add'])->name('admin.add');
   
    Route::get('/{id}', [AdminController::class, 'showStagiaire'])->name('admin.show');
    Route::get('/accepte/{id}', [AdminController::class, 'AcceptStagiaire'])->name('admin.accept');
    Route::match(['get', 'post'], '/refuse/{id}', [AdminController::class, 'RefuseStagiaire'])->name('admin.refuse');
    Route::put('/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
});

 Route::post('admin/ajouter', [AdminController::class, 'ajouter']);
