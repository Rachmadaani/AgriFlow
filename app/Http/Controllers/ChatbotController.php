<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500'
        ]);

        $question = $request->question;

        // Konteks pertanian user
        $context = "Petani dengan lahan sendiri, tenaga 1 orang. ";
        $context .= "Hasil panen: " . $this->getHarvestSummary() . ". ";
        $context .= "Jawab pertanyaan tentang pertanian (cabai, timun, dll) dengan singkat dan praktis.";

        // Panggil API OpenRouter (DeepSeek FREE)
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'Content-Type' => 'application/json',
                'HTTP-Referer' => env('APP_URL'),
                'X-Title' => 'AgriFlow AI'
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'deepseek/deepseek-chat', // atau 'meta-llama/llama-3-2-3b-instruct:free'
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Anda adalah asisten pertanian profesional untuk petani Indonesia. Jawab singkat, padat, dan praktis dalam Bahasa Indonesia. Berikan solusi konkret.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Konteks: $context\n\nPertanyaan: $question"
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7
            ]);

            if ($response->successful()) {
                $reply = $response->json()['choices'][0]['message']['content'];
            } else {
                $reply = "Maaf, AI sedang sibuk. Coba lagi nanti ya. Error: " . $response->status();
            }
        } catch (\Exception $e) {
            $reply = "Maaf, terjadi masalah koneksi ke AI. Pastikan koneksi internet stabil.";
        }

        return response()->json([
            'reply' => $reply,
            'question' => $question
        ]);
    }

    private function getHarvestSummary()
    {
        $user = Auth::user();
        $totalHarvest = \App\Models\Harvest::where('user_id', $user->id)
            ->whereMonth('date', now()->month)
            ->sum('weight_kg');

        $topPlant = \App\Models\Harvest::where('user_id', $user->id)
            ->with('plant')
            ->selectRaw('plant_id, SUM(weight_kg) as total')
            ->groupBy('plant_id')
            ->orderBy('total', 'desc')
            ->first();

        $summary = "Total panen bulan ini " . number_format($totalHarvest, 1) . " kg. ";
        if ($topPlant) {
            $summary .= "Komoditas utama: " . ($topPlant->plant->name ?? 'belum ada') . ". ";
        }
        return $summary;
    }
}
