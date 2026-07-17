<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\Models\Experience;

class ChatbotController extends Controller
{
    /**
     * Chatbot Admin - Análisis de datos y recomendaciones
     */
    public function chat(Request $request)
    {
        $message = $request->input('message');

        if (!$message) {
            return response()->json(['error' => 'Mensaje vacío'], 400);
        }

        $response = $this->processAdminQuery($message);

        return response()->json([
            'user_query' => $message,
            'bot_response' => $response,
            'timestamp' => now(),
            'type' => 'admin_chatbot'
        ]);
    }

    private function processAdminQuery($message)
    {
        $lower = strtolower($message);

        // ANÁLISIS DE VENTAS
        if (strpos($lower, 'producto más vendido') !== false) {
            return $this->getMostSoldProduct();
        }

        if (strpos($lower, 'ingreso') !== false || strpos($lower, 'ganancias') !== false) {
            return $this->getTotalRevenue();
        }

        if (strpos($lower, 'pedidos') !== false) {
            return $this->getOrderStats();
        }

        if (strpos($lower, 'más valorado') !== false) {
            return $this->getTopRatedProduct();
        }

        // RECOMENDACIONES
        if (strpos($lower, 'stock') !== false) {
            return $this->getStockAlerts();
        }

        if (strpos($lower, 'usuarios inactivos') !== false) {
            return $this->getInactiveUsers();
        }

        if (strpos($lower, 'malas calificaciones') !== false) {
            return $this->getLowRatedProducts();
        }

        // REPORTES
        if (strpos($lower, 'resumen') !== false || strpos($lower, 'reporte') !== false) {
            return $this->getMonthlyReport();
        }

        if (strpos($lower, 'tendencia') !== false) {
            return $this->getRevenuesTrend();
        }

        // BÚSQUEDA
        if (strpos($lower, 'prestador') !== false) {
            return $this->getTopProvider();
        }

        if (strpos($lower, 'experiencia') !== false) {
            return $this->getExperienceStats();
        }

        // SOPORTE
        if (strpos($lower, 'ayuda') !== false || strpos($lower, 'cómo') !== false) {
            return $this->getHelp();
        }

        return "Entiendo que preguntas sobre: '$message'. Puedo ayudarte con: ventas, ingresos, pedidos, usuarios, stock, reportes y más. ¿Qué necesitas?";
    }

    private function getMostSoldProduct()
{
    $experience = Experience::withCount('orders')
        ->orderByDesc('orders_count')
        ->first();

    if (!$experience) {
        return "No hay productos con ventas aún.";
    }

    return "El producto MÁS VENDIDO es: **{$experience->title}** con " . ($experience->orders_count ?? 0) . " ventas. Precio: \${$experience->price}";
}

    private function getTotalRevenue()
    {
        $total = Order::sum('total_price');
        $count = Order::count();

        return "📊 **INGRESOS TOTALES:** \${$total}\n- Total de pedidos: {$count}\n- Promedio por pedido: \$" . round($total / ($count ?: 1), 2);
    }

    private function getOrderStats()
    {
        $completed = Order::where('status', 'completed')->count();
        $confirmed = Order::where('status', 'confirmed')->count();
        $pending = Order::where('status', 'pending')->count();
        $cancelled = Order::where('status', 'cancelled')->count();

        return "📦 **ESTADO DE PEDIDOS:**\n- Completados: {$completed}\n- Confirmados: {$confirmed}\n- Pendientes: {$pending}\n- Cancelados: {$cancelled}";
    }

    private function getTopRatedProduct()
    {
        $reviews = Review::selectRaw('experience_id, AVG(rating) as avg_rating, COUNT(*) as count')
            ->groupBy('experience_id')
            ->orderByDesc('avg_rating')
            ->first();

        if (!$reviews) {
            return "No hay reseñas aún.";
        }

        $experience = Experience::find($reviews->experience_id);
        $rating = round($reviews->avg_rating, 1);

        return "⭐ **EXPERIENCIA MÁS VALORADA:** {$experience->title}\n- Rating: {$rating}/5\n- Reseñas: {$reviews->count}";
    }

    private function getStockAlerts()
    {
        $low_stock = Article::where('stock', '<', 5)->get();

        if ($low_stock->isEmpty()) {
            return "✅ Todos los productos tienen stock suficiente.";
        }

        $alert = "⚠️ **PRODUCTOS CON STOCK BAJO:**\n";
        foreach ($low_stock as $item) {
            $alert .= "- {$item->name}: {$item->stock} unidades\n";
        }

        return $alert;
    }

    private function getInactiveUsers()
    {
        $inactive = User::where('created_at', '<', now()->subDays(30))->count();

        return "👥 **USUARIOS INACTIVOS:** {$inactive} usuarios no han realizado compras en 30 días. Considera enviarles una promoción.";
    }

    private function getLowRatedProducts()
    {
        $low = Review::selectRaw('experience_id, AVG(rating) as avg_rating')
            ->havingRaw('AVG(rating) < 3')
            ->groupBy('experience_id')
            ->get();

        if ($low->isEmpty()) {
            return "✅ Todos los productos tienen buenas calificaciones.";
        }

        $alert = "⚠️ **PRODUCTOS CON BAJA CALIFICACIÓN:**\n";
        foreach ($low as $item) {
            $exp = Experience::find($item->experience_id);
            $alert .= "- {$exp->title}: " . round($item->avg_rating, 1) . "/5\n";
        }

        return $alert;
    }

    private function getMonthlyReport()
    {
        $orders = Order::whereMonth('order_date', now()->month)->count();
        $revenue = Order::whereMonth('order_date', now()->month)->sum('total_price');
        $reviews = Review::whereMonth('created_at', now()->month)->count();

        return "📈 **REPORTE DEL MES ACTUAL:**\n- Pedidos: {$orders}\n- Ingresos: \${$revenue}\n- Nuevas reseñas: {$reviews}\n- Promedio por pedido: \$" . round($revenue / ($orders ?: 1), 2);
    }

    private function getRevenuesTrend()
    {
        $lastMonth = Order::whereMonth('order_date', now()->subMonth()->month)->sum('total_price');
        $thisMonth = Order::whereMonth('order_date', now()->month)->sum('total_price');

        $change = $thisMonth - $lastMonth;
        $percentage = ($change / ($lastMonth ?: 1)) * 100;

        $trend = $change >= 0 ? '📈 Crecimiento' : '📉 Decrecimiento';

        return "$trend: {$percentage}%\n- Mes anterior: \${$lastMonth}\n- Este mes: \${$thisMonth}";
    }

    private function getTopProvider()
    {
        $provider = Experience::withCount('orders')
            ->orderByDesc('orders_count')
            ->first();

        if (!$provider) {
            return "No hay proveedores con reservas aún.";
        }

        $user = User::find($provider->user_id);

        return "👑 **PRESTADOR TOP:** {$user->name}\n- Experiencia: {$provider->title}\n- Reservas: " . ($provider->orders_count ?? 0);
    }

    private function getExperienceStats()
    {
        $total = Experience::count();
        $active = Experience::where('active', true)->count();

        return "🎯 **ESTADÍSTICAS DE EXPERIENCIAS:**\n- Total: {$total}\n- Activas: {$active}\n- Inactivas: " . ($total - $active);
    }

    private function getHelp()
    {
        return "ℹ️ **FUNCIONES DEL CHATBOT ADMIN:**\n\n" .
               "💰 Ventas:\n" .
               "  • 'Producto más vendido'\n" .
               "  • 'Ingresos totales'\n" .
               "  • 'Pedidos'\n\n" .
               "⭐ Calificaciones:\n" .
               "  • 'Más valorado'\n" .
               "  • 'Malas calificaciones'\n\n" .
               "⚠️ Alertas:\n" .
               "  • 'Stock bajo'\n" .
               "  • 'Usuarios inactivos'\n\n" .
               "📊 Reportes:\n" .
               "  • 'Resumen del mes'\n" .
               "  • 'Tendencia'\n\n" .
               "¿Qué necesitas?";
    }
}