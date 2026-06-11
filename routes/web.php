<?php


use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung diarahkan ke interface chat
Route::get('/chatbot', [ChatbotController::class, 'index']);

// Route post khusus penanganan request chat dari AJAX JavaScript
Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage']);
