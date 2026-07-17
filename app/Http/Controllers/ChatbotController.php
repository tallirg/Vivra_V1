<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{

    public function preguntar(Request $request)
    {

        $request->validate([
            'mensaje'=>'required|string'
        ]);


        $mensaje = $request->mensaje;


        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'),
            [
                "contents"=>[
                    [
                        "parts"=>[
                            [
                                "text"=>
                                "
                                Eres el asistente virtual de la tienda Vivra.

                                Ayudas a clientes con:
                                - productos
                                - precios
                                - compras
                                - dudas generales

                                Responde de manera amable y breve.

                                Pregunta del cliente:
                                ".$mensaje
                            ]
                        ]
                    ]
                ]
            ]
        );


        if($response->failed())
        {
            return response()->json([
                "error"=>"Error al conectar con Gemini",
                "detalle"=>$response->json()
            ],500);
        }


        $data=$response->json();


        return response()->json([
            "pregunta"=>$mensaje,
            "respuesta"=>$data["candidates"][0]["content"]["parts"][0]["text"]
        ]);

    }

}