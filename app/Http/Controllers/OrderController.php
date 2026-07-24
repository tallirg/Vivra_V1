<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalExperiences = Article::count();
        $pendingExperiences = Article::where('active', false)->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price') ?? 0;

        $latestOrders = Order::with(['user', 'experience'])->latest()->take(5)->get();

        // 1. Datos reales para la gráfica: Reservas por Mes (Últimos 6 meses)
        $monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $chartMonths = [];
        $monthlyData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartMonths[] = $monthNames[$date->month - 1];

            $monthlyData[] = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // 2. Datos reales para la gráfica: Experiencias registradas
        $articles = Article::take(5)->get();
        $expLabels = $articles->pluck('name')->toArray();
        $expData = [];

        foreach ($articles as $art) {
            $expData[] = Order::where('experience_id', $art->id)->count();
        }

        if (empty($expLabels)) {
            $expLabels = ['Sin Experiencias'];
            $expData = [0];
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalExperiences',
            'pendingExperiences',
            'totalOrders',
            'totalRevenue',
            'latestOrders',
            'chartMonths',
            'monthlyData',
            'expLabels',
            'expData'
        ));
    }

    public function index()
    {
        $orders = Order::with(['user', 'experience'])->get();
        return view('admin.orders', compact('orders'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return redirect('/admin/orders');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('success', 'Reservación eliminada con éxito de la base de datos.');
    }
}