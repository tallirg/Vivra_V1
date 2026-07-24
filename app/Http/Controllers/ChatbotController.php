<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Article;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'La API Key de Groq no está configurada.'], 500);
        }

        // Consultamos el catálogo
        $experiences = Article::select('name', 'description', 'price')->take(10)->get();
        
        $contextText = "Catálogo de experiencias en Vivra (Oaxaca):\n";
        foreach ($experiences as $exp) {
            $contextText .= "- {$exp->name}: {$exp->description} (\${$exp->price} MXN)\n";
        }

        $systemPrompt = "Eres el asistente virtual amable de la plataforma turística Vivra en Oaxaca. "
            . "Responde de forma breve, útil y cordial usando este catálogo:\n\n" . $contextText;

        try {
            // Endpoint de Groq (Compatible con formato OpenAI)
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $reply = $response->json('choices.0.message.content');
                return response()->json(['reply' => trim($reply)], 200);
            }

            return response()->json([
                'error' => 'Error al comunicarse con la IA.',
                'details' => $response->json()
            ], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}