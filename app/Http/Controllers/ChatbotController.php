<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');

        try {
            // Jembatan: Menembak API Flask lokal port 5000 yang sudah kita perbaiki tadi
            $response = Http::timeout(15)->post('https://alamat-flask-kamu-di-cloud.onrender.com/api/chatbot', [
                'message' => $userMessage
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'reply' => '⚠️ Server AI (Flask) mengembalikan respons error 500.',
                'intent' => 'error'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'reply' => '⚠️ Gagal terhubung ke engine AI Chatbot Flask. Pastikan app.py sudah dijalankan di terminal!',
                'intent' => 'error'
            ]);
        }
    }
}
