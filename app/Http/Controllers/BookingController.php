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
        $request->validate([
            'experience_id' => 'required|exists:articles,id',
        ]);

        // <-- 3. Usamos Order::create para guardar en la tabla orders
        $order = Order::create([
            'user_id' => Auth::id(), 
            'experience_id' => $request->experience_id,
            'status' => 'confirmed', 
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