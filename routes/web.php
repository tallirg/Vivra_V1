<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect('/login');
});

    // Admin Panel - Protegido
    Route::middleware(['auth'])->group(function () {
    
    Route::get('/admin/dashboard', function () {
        $totalUsers = \App\Models\User::count();
        $totalExperiences = \App\Models\Article::count();
        $pendingExperiences = \App\Models\Article::where('active', false)->count();
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\Order::sum('total_price') ?? 0;
        $latestOrders = \App\Models\Order::with(['user', 'experience'])->latest()->take(5)->get();

        // 1. Datos reales para la gráfica: Reservas por Mes (Últimos 6 meses)
        $monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $chartMonths = [];
        $monthlyData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $chartMonths[] = $monthNames[$date->month - 1];
            
            // Cuenta las reservaciones reales de cada mes en SQLite
            $monthlyData[] = \App\Models\Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // 2. Datos reales para la gráfica: Experiencias registradas
        $articles = \App\Models\Article::take(5)->get();
        $expLabels = $articles->pluck('name')->toArray();
        
        $expData[] = \App\Models\Order::where('experience_id', $art->id)->count();

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
    });

    Route::resource('admin/articles', ArticleController::class);
    Route::resource('admin/categories', CategoryController::class);
    Route::resource('admin/brands', BrandController::class);
    Route::resource('admin/orders', OrderController::class);
    Route::resource('admin/reviews', ReviewController::class);
    // Listar usuarios con Filtros y Buscador
    Route::get('admin/users', function (Illuminate\Http\Request $request) {
        $query = App\Models\User::query();

        // Filtro de búsqueda por Nombre o Email
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por Rol específico
        if ($request->filled('role') && $request->role !== 'Todos los roles') {
            $query->where('role', $request->role);
        }

        $users = $query->get();
        return view('admin.users', compact('users'));
    });

    // Crear Nuevo Usuario desde el Formulario
    Route::post('admin/users', function (Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string',
            'password' => 'required|string|min:4'
        ]);

        App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect('admin/users')->with('success', 'Usuario creado con éxito');
    });

    // Eliminar Usuario
    Route::delete('admin/users/{id}', function ($id) {
        App\Models\User::destroy($id);
        return redirect('admin/users');
    });
});
