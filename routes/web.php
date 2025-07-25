<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\PublicacioneController;
use App\Http\Controllers\AutenticacionController;

//FFianquetto V.6

Route::get('/', function () {
    return redirect()->route('auth.login');
});

Route::get('/login', [AutenticacionController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AutenticacionController::class, 'login'])->name('auth.login.post');
Route::post('/logout', [AutenticacionController::class, 'logout'])->name('auth.logout');

Route::get('/dashboard', [AutenticacionController::class, 'dashboard'])->name('auth.dashboard');

Route::get('/registros/create', [RegistroController::class, 'create'])->name('registros.create');
Route::post('/registros', [RegistroController::class, 'store'])->name('registros.store');

Route::get('/comentarios', [ComentarioController::class, 'index'])->name('comentarios.index');
Route::get('/comentarios/create', [ComentarioController::class, 'create'])->name('comentarios.create');
Route::post('/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
Route::get('/comentarios/{id}', [ComentarioController::class, 'show'])->name('comentarios.show');

Route::get('/publicaciones', [PublicacioneController::class, 'index'])->name('publicaciones.index');
Route::get('/publicaciones/create', [PublicacioneController::class, 'create'])->name('publicaciones.create');
Route::post('/publicaciones', [PublicacioneController::class, 'store'])->name('publicaciones.store');

Route::get('/publicaciones/{id}/edit', [PublicacioneController::class, 'edit'])->name('publicaciones.edit');
Route::put('/publicaciones/{id}', [PublicacioneController::class, 'update'])->name('publicaciones.update');

Route::delete('/publicaciones/{id}', [PublicacioneController::class, 'destroy'])->name('publicaciones.destroy');

Route::get('/publicaciones/{id}', [PublicacioneController::class, 'show'])->name('publicaciones.show');

Route::get('/conversacion/{usuario1}/{usuario2}', [ComentarioController::class, 'conversacion'])->name('comentarios.conversacion');
