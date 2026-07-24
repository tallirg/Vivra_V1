<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Article;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalExperiences = Article::count();
        $pendingExperiences = Article::where('active', false)->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price') ?? 0;

        $latestOrders = Order::with(['user', 'experience'])->latest()->take(5)->get();

        // Datos para la gráfica de reservas por mes
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

        // Datos para la gráfica de experiencias
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
}