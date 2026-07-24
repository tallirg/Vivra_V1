<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Article;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        // 1. Validar el mensaje de entrada
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            return response()->json([
                'error' => 'La API Key de Gemini no está configurada.'
            ], 500);
        }

        // 2. Traer las experiencias activas de la BD para darle contexto real a la IA
        // Enviar solo name, description y price evita consumir tokens innecesarios
        $experiences = Article::select('name', 'description', 'price')->take(10)->get();
        $contextText = "Experiencias disponibles en la plataforma Vivra:\n";
        foreach ($experiences as $exp) {
            $contextText .= "- {$exp->name}: {$exp->description} (Precio: \${$exp->price} MXN)\n";
        }

        // 3. Prompt de sistema
        $systemInstruction = "Eres un asistente virtual amable y servicial de la plataforma turística Vivra en Oaxaca. "
            . "Tu objetivo es ayudar a los usuarios recomendándoles experiencias turísticas locales de nuestro catálogo. "
            . "Usa la siguiente información de nuestro catálogo para responder de forma breve, clara y amigable:\n\n"
            . $contextText;

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        try {
            $response = Http::post($url, [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $userMessage]
                        ]
                    ]
                ]
            ]);

            // 1. Respuesta exitosa
            if ($response->successful()) {
                $reply = $response->json('candidates.0.content.parts.0.text') 
                    ?? 'Lo siento, no pude procesar tu consulta en este momento.';

                return response()->json([
                    'reply' => trim($reply)
                ], 200);
            }

            // 2. Si se excede el límite de peticiones por minuto (429)
            if ($response->status() === 429) {
                return response()->json([
                    'reply' => 'El asistente está recibiendo muchas consultas en este momento. Por favor, intenta de nuevo en un minuto.'
                ], 200);
            }

            // 3. Otros errores
            return response()->json([
                'error' => 'Ocurrió un problema con el servicio de IA.',
                'details' => $response->json()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error de conexión con Gemini.',
                'message' => $e->getMessage()
            ], 500);
        }catch (\Exception $e) {
            return response()->json([
                'error' => 'Error de conexión con Gemini.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}