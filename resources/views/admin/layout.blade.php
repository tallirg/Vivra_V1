<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Vivra Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        warm: {
                            50: '#FEF5E7',
                            100: '#FAF3E0',
                            200: '#F5E6D3',
                            300: '#EDD4B9',
                            400: '#D97706',
                            500: '#B45309',
                            600: '#92400E',
                            700: '#78350F',
                        },
                        accent: {
                            cream: '#FFFBF0',
                            beige: '#F5EDE4',
                            sand: '#E8DCC8',
                            gold: '#D97706',
                            dark: '#78350F',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(135deg, #FEF5E7 0%, #FAF3E0 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .sidebar {
            background: linear-gradient(180deg, #F5E6D3 0%, #FAF3E0 100%);
        }
        .header {
            background: linear-gradient(135deg, #FFFBF0 0%, #F5EDE4 100%);
            border-bottom: 2px solid #E8DCC8;
        }
        .card {
            background: linear-gradient(135deg, #FFFBF0 0%, #FFFAF4 100%);
            border: 1px solid #E8DCC8;
            box-shadow: 0 4px 15px rgba(139, 64, 14, 0.08);
        }
        .btn-primary {
            background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #B45309 0%, #92400E 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 64, 14, 0.2);
        }
        .nav-item {
            color: #78350F;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-item:hover {
            background: rgba(217, 119, 6, 0.1);
            color: #D97706;
        }
        .nav-item.active {
            background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
            color: white;
        }
        .stat-card {
            background: linear-gradient(135deg, #FFFBF0 0%, #FAF3E0 100%);
            border-left: 4px solid #D97706;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(139, 64, 14, 0.08);
        }
        .chart-container {
            background: linear-gradient(135deg, #FFFBF0 0%, #FFFAF4 100%);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #E8DCC8;
        }
        .badge-success {
            background: linear-gradient(135deg, #DCFCE7 0%, #BBFBDF 100%);
            color: #15803D;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-pending {
            background: linear-gradient(135deg, #FEF3C7 0%, #FEFCE8 100%);
            color: #92400E;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-confirmed {
            background: linear-gradient(135deg, #DBEAFE 0%, #E0F2FE 100%);
            color: #0C4A6E;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="flex h-screen bg-warm-50">
        <!-- Sidebar -->
        <div class="w-72 sidebar overflow-y-auto">
            <div class="p-8 border-b-2 border-accent-sand">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">V</div>
                    <div>
                        <h1 class="text-2xl font-bold text-warm-700">Vivra</h1>
                        <p class="text-xs text-warm-600 mt-1">Panel Administrativo</p>
                    </div>
                </div>
            </div>
            
            <nav class="p-6 space-y-2">
                <div class="text-xs font-bold text-warm-600 uppercase tracking-wider mb-4 px-4">Menú</div>
                
                <a href="/admin/dashboard" class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line w-5"></i> Dashboard
                </a>
                <a href="/admin/articles" class="nav-item {{ request()->is('admin/articles') ? 'active' : '' }}">
                    <i class="fas fa-box w-5"></i> Experiencias
                </a>
                <a href="/admin/categories" class="nav-item">
                    <i class="fas fa-tag w-5"></i> Categorías
                </a>
                <a href="/admin/brands" class="nav-item">
                    <i class="fas fa-trademark w-5"></i> Prestadores Locales
                </a>
                <a href="/admin/orders" class="nav-item {{ request()->is('admin/orders') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart w-5"></i> Reservaciones
                </a>
                <a href="/admin/users" class="nav-item {{ request()->is('admin/users') ? 'active' : '' }}">
                    <i class="fas fa-users w-5"></i> Usuarios
                </a>
                <a href="/admin/reviews" class="nav-item {{ request()->is('admin/reviews') ? 'active' : '' }}">
                    <i class="fas fa-star w-5"></i> Reseñas
                </a>
            </nav>

            <div class="p-6 border-t-2 border-accent-sand">
                <div class="bg-gradient-to-br from-orange-100 to-orange-50 p-4 rounded-lg flex items-center justify-between gap-3">
                    <!-- Avatar e Info del Admin -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold">
                            A
                        </div>
                        <div>
                            <p class="text-sm font-bold text-warm-700">Admin</p>
                            <p class="text-xs text-warm-600">en línea</p>
                        </div>
                    </div>

                    <!-- Botón para Cerrar Sesión -->
                    <form action="/logout" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="text-warm-600 hover:text-red-600 p-2 rounded-lg transition" title="Cerrar Sesión">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <div class="header">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h2 class="text-3xl font-bold text-warm-800">@yield('header', 'Dashboard')</h2>
                        <p class="text-sm text-warm-600 mt-1">Bienvenido de vuelta</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="px-4 py-2 bg-warm-200 text-warm-700 rounded-lg hover:bg-warm-300 transition font-semibold text-sm">Exportar</button>
                        <button class="btn-primary">+ Nuevo</button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto p-8">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>