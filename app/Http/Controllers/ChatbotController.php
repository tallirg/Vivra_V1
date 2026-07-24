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
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            return response()->json(['error' => 'La API Key de Gemini no está configurada.'], 500);
        }

        // 1. Tomamos máximo 10 experiencias para no saturar tokens
        $experiences = Article::select('name', 'description', 'price')->take(10)->get();
        
        $contextText = "Catálogo de experiencias en Vivra (Oaxaca):\n";
        foreach ($experiences as $exp) {
            $contextText .= "- {$exp->name}: {$exp->description} (\${$exp->price} MXN)\n";
        }

        // 2. Unificamos las instrucciones y el mensaje en un solo Prompt
        $promptCompleto = "Eres el asistente virtual amable de la plataforma turística Vivra en Oaxaca. "
            . "Responde de forma breve, útil y cordial usando este catálogo de experiencias:\n\n"
            . $contextText . "\n\n"
            . "Pregunta del usuario: " . $userMessage;

        // 🟢 Cambiamos al modelo gemini-2.0-flash-lite
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-lite:generateContent?key={$apiKey}";

        try {
            $response = Http::post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $promptCompleto]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $reply = $response->json('candidates.0.content.parts.0.text') 
                    ?? 'Lo siento, no pude procesar tu consulta en este momento.';

                return response()->json([
                    'reply' => trim($reply)
                ], 200);
            }

            /*if ($response->status() === 429) {
                return response()->json([
                    'reply' => 'El servidor de IA está saturado en este momento. Intenta de nuevo en unos momentos.'
                ], 200);
            }*/

            return response()->json([
                'error' => 'Ocurrió un problema con el servicio de IA.',
                'details' => $response->json()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error de conexión con Gemini.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}