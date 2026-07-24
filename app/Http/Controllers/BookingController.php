<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Article;
use App\Models\ArticleSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // 🌟 VITAL: La herramienta matemática para sumar horas

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validación básica de los datos que llegan desde Flutter
        $request->validate([
            'experience_id' => 'required|exists:articles,id',
            'schedule_id'   => 'required|exists:article_schedules,id',
            'booking_date'  => 'required|date',
            'quantity'      => 'required|integer|min:1',
        ]);

        $article = Article::findOrFail($request->experience_id);
        $schedule = ArticleSchedule::findOrFail($request->schedule_id);

        // =========================================================
        // FILTRO 1: CAPACIDAD (STOCK)
        // =========================================================
        $lugaresOcupados = Order::where('schedule_id', $schedule->id)
            ->where('booking_date', $request->booking_date)
            ->where('status', 'confirmed')
            ->sum('quantity');

        if (($lugaresOcupados + $request->quantity) > $schedule->stock) {
            return response()->json([
                'message' => 'Lugares insuficientes. Solo quedan ' . ($schedule->stock - $lugaresOcupados) . ' lugares disponibles.'
            ], 422);
        }

        // =========================================================
        // FILTRO 2: CRUCE DE HORARIOS
        // =========================================================
        
        // Calculamos a qué hora terminas tu nueva experiencia
        $nuevoInicio = Carbon::parse($schedule->start_time);
        $nuevoFin = $nuevoInicio->copy()->addMinutes($article->duration_minutes);

        // Traemos todas las reservas de este turista para el mismo día
        $reservasDelDia = Order::with('schedule.article')
            ->where('user_id', Auth::id())
            ->where('booking_date', $request->booking_date)
            ->where('status', 'confirmed')
            ->get();

        foreach ($reservasDelDia as $reservaVieja) {
            // Saltamos las órdenes viejas que no tengan horario (las de prueba que hicimos antes)
            if (!$reservaVieja->schedule) continue;

            $viejoInicio = Carbon::parse($reservaVieja->schedule->start_time);
            $viejoFin = $viejoInicio->copy()->addMinutes($reservaVieja->schedule->article->duration_minutes);

            // Fórmula matemática de empalme: (Nuevo Inicio < Viejo Fin) Y (Nuevo Fin > Viejo Inicio)
            if ($nuevoInicio < $viejoFin && $nuevoFin > $viejoInicio) {
                return response()->json([
                    'message' => 'Cruce de horarios detectado. Esta experiencia choca con otra reservación que tienes de ' 
                                 . $viejoInicio->format('H:i') . ' a ' . $viejoFin->format('H:i')
                ], 422);
            }
        }

        // =========================================================
        // FILTRO 3: PRECIO DINÁMICO
        // =========================================================
        $precioBase = $article->price ?? 0;
        $personasExtra = max(0, $request->quantity - $article->included_persons);
        $costoExtra = $personasExtra * $article->extra_person_price;
        $precioTotal = $precioBase + $costoExtra;

        // =========================================================
        // GUARDAR LA RESERVA
        // =========================================================
        $order = Order::create([
            'user_id'        => Auth::id(),
            'experience_id'  => $article->id,
            'schedule_id'    => $schedule->id,
            'booking_date'   => $request->booking_date,
            'quantity'       => $request->quantity,
            'total_price'    => $precioTotal,
            'status'         => 'confirmed',
            'payment_method' => 'tarjeta',
            'order_date'     => now(),
            'notes'          => 'Reserva validada correctamente'
        ]);

        // Regresamos la orden con su horario cargado para que Flutter la muestre completa
        return response()->json([
            'message' => 'Reserva confirmada exitosamente',
            'order'   => $order->load('schedule.article')
        ], 201);
    }

    public function myBookings()
    {
        // Actualizamos esta función para que ahora jale la información del horario (schedule) también
        $orders = Order::with(['experience', 'schedule'])->where('user_id', Auth::id())->get();

        return response()->json([
            'data' => $orders
        ], 200);
    }

    // Función para obtener los horarios de una experiencia en específico
    public function getSchedules($experience_id)
    {
        // Buscamos todos los horarios que pertenezcan al ID de esta experiencia
        $schedules = \App\Models\ArticleSchedule::where('article_id', $experience_id)
            ->orderBy('start_time', 'asc') // Los ordenamos de más temprano a más tarde
            ->get();

        // Verificamos si la experiencia tiene horarios registrados
        if ($schedules->isEmpty()) {
            return response()->json([
                'message' => 'No hay horarios disponibles para esta experiencia.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Horarios recuperados con éxito',
            'data' => $schedules
        ], 200);
    }
    public function providerBookings()
    {
        $providerId = Auth::id();

        // Buscamos las órdenes cuyas experiencias pertenezcan a este prestador
        $orders = Order::with(['experience', 'user', 'schedule'])
            ->whereHas('experience', function ($query) use ($providerId) {
                // Filtra por el ID del prestador (revisa brand_id o user_id)
                $query->where('brand_id', $providerId)
                      ->orWhere('user_id', $providerId);
            })
            ->latest() // Muestra las más recientes primero
            ->get();

        return response()->json([
            'data' => $orders
        ], 200);
    }
}