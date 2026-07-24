<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController; // 👈 Importamos el nuevo controlador

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect('/login');
});

// Admin Panel - Protegido
Route::middleware(['auth'])->group(function () {
    
    // Dashboard administrado por su propio controlador
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // CRUDs
    Route::resource('admin/articles', ArticleController::class);
    Route::resource('admin/categories', CategoryController::class);
    Route::resource('admin/brands', BrandController::class);
    Route::resource('admin/orders', OrderController::class);
    Route::resource('admin/reviews', ReviewController::class);

    // Rutas de Usuarios...
    Route::get('admin/users', function (Illuminate\Http\Request $request) {
        $query = App\Models\User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role') && $request->role !== 'Todos los roles') {
            $query->where('role', $request->role);
        }

        $users = $query->get();
        return view('admin.users', compact('users'));
    });

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

    Route::delete('admin/users/{id}', function ($id) {
        App\Models\User::destroy($id);
        return redirect('admin/users');
    });
});