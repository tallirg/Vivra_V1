<?php

namespace App\Http\Controllers;

use App\Models\Order; // <-- 1. Mandamos a llamar a tu modelo Order
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller // <-- 2. Mantenemos el nombre para que api.php no falle
{
    // Función para crear una nueva reserva (comprar)
public function store(Request $request)
    {
        // 1. Validamos buscando en la tabla correcta (articles)
        $request->validate([
            'experience_id' => 'required|exists:articles,id',
        ]);

        // 2. Creamos la orden llenando TODOS los campos obligatorios
        $order = Order::create([
            'user_id' => Auth::id(), 
            'experience_id' => $request->experience_id,
            'quantity' => $request->slots ?? 1, // Recibimos los slots desde Flutter
            'total_price' => 0, // Lo dejamos en 0 por ahora para que no marque error
            'status' => 'confirmed', 
            'payment_method' => 'tarjeta',
            'order_date' => now(), // Genera la fecha y hora actual automáticamente
            'notes' => 'Reserva desde la app móvil'
        ]);

        return response()->json([
            'message' => 'Reserva creada exitosamente',
            'order' => $order
        ], 201);
    }

    // Función para devolver las reservas a la pestaña de "Mis reservas"
    public function myBookings()
    {
        // <-- 4. Buscamos en la tabla orders
        $orders = Order::with('experience')->where('user_id', Auth::id())->get();

        return response()->json([
            'data' => $orders
        ], 200);
    }
}