<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Experience; // <-- IMPORTAMOS EL MODELO REAL EN LUGAR DE DB

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 1. Obtenemos el usuario (turista) directamente desde el Token de Sanctum
        $user = $request->user();

        // 2. Buscamos la primera experiencia disponible usando Eloquent de Mongo
        $experience = Experience::first();

        if (!$experience) {
            return response()->json([
                'error' => 'No se pudo procesar la compra porque no hay experiencias registradas en MongoDB.'
            ], 404);
        }

        // 3. Insertamos el documento de la compra en la colección 'bookings'
        $booking = Booking::create([
            'turista_id' => $user->_id,
            'experiencia_id' => $experience->_id, // ID dinámico de Mongo
            'titulo_experiencia' => $experience->titulo ?? 'Tour en Oaxaca',
            'total_pago' => $experience->precio ?? 0,
            'estatus' => 'confirmada' 
        ]);

        // 4. Respondemos al Frontend con éxito
        return response()->json([
            'mensaje' => '¡Compra realizada con éxito desde el carrito!',
            'detalles_de_la_reserva' => $booking
        ], 201);
    }
}
