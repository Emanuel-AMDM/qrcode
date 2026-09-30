<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route;

// Página de Apresentação / Landing
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Autenticação Tradicional
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');

// Autenticação com Google (Gmail OAuth)
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Redirecionamento público de QR Code Dinâmico (com rate limiting anti-DoS)
Route::get('/q/{slug}', [QrCodeController::class, 'redirect'])->name('qr.redirect')->middleware('throttle:60,1');

// Rotas Autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Painel e Gerador de QR Code
    Route::get('/qr-codes', [QrCodeController::class, 'index'])->name('qr.index');
    Route::post('/qr-codes', [QrCodeController::class, 'store'])->name('qr.store');
    Route::put('/qr-codes/{qrCode}', [QrCodeController::class, 'update'])->name('qr.update');
    Route::delete('/qr-codes/{qrCode}', [QrCodeController::class, 'destroy'])->name('qr.destroy');
    Route::get('/qr-codes/{qrCode}/stats', [QrCodeController::class, 'stats'])->name('qr.stats');
});
