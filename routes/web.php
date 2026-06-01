<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Tanaman
    Route::resource('plants', PlantController::class);

    // Manajemen Panen
    Route::resource('harvests', HarvestController::class);

    // Manajemen Pengeluaran
    Route::resource('expenses', ExpenseController::class);

    // Laporan
    Route::get('/reports/profit', [DashboardController::class, 'profitReport'])->name('reports.profit');

    // AI Chatbot
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
