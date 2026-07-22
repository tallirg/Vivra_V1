@extends('admin.layout')

@section('title', 'Dashboard')
@section('header', 'Dashboard - Panel Principal')

@section('content')
<div class="space-y-8">
    <!-- Estadísticas Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-warm-600 text-sm font-semibold uppercase tracking-wider">Total Usuarios</p>
                    <p class="text-4xl font-bold text-warm-800 mt-3">{{ $totalUsers }}</p>
                </div>
                <i class="fas fa-users text-orange-400 text-4xl opacity-30"></i>
            </div>
            <p class="text-green-600 text-sm mt-4 font-semibold">Registrados en la plataforma</p>
        </div>

        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-warm-600 text-sm font-semibold uppercase tracking-wider">Total Experiencias</p>
                    <p class="text-4xl font-bold text-warm-800 mt-3">{{ $totalExperiences }}</p>
                </div>
                <i class="fas fa-map-pin text-blue-400 text-4xl opacity-30"></i>
            </div>
            <p class="{{ $pendingExperiences > 0 ? 'text-amber-600' : 'text-green-600' }} text-sm mt-4 font-semibold">
                {{ $pendingExperiences }} pendientes de aprobación
            </p>
        </div>

        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-warm-600 text-sm font-semibold uppercase tracking-wider">Reservaciones Totales</p>
                    <p class="text-4xl font-bold text-warm-800 mt-3">{{ $totalOrders }}</p>
                </div>
                <i class="fas fa-shopping-cart text-orange-500 text-4xl opacity-30"></i>
            </div>
            <p class="text-green-600 text-sm mt-4 font-semibold">Procesadas en el sistema</p>
        </div>

        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-warm-600 text-sm font-semibold uppercase tracking-wider">Ingresos Totales</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent mt-3">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <i class="fas fa-dollar-sign text-yellow-500 text-4xl opacity-30"></i>
            </div>
            <p class="text-green-600 text-sm mt-4 font-semibold">Acumulado de reservas</p>
        </div>
    </div>

    <!-- Gráficas Dinámicas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="chart-container">
            <h3 class="text-lg font-bold text-warm-800 mb-6">Tendencia de Reservas</h3>
            <div style="position: relative; height: 250px;">
                <canvas id="chartVentas"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <h3 class="text-lg font-bold text-warm-800 mb-6">Experiencias Populares</h3>
            <div style="position: relative; height: 250px;">
                <canvas id="chartExperiencias"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabla: Últimas Reservaciones -->
    <div class="card">
        <div class="p-6 border-b-2 border-warm-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-warm-800">Últimas Reservaciones</h3>
            <a href="{{ url('admin/orders') }}" class="text-orange-600 hover:text-orange-700 text-sm font-semibold">Ver todas →</a>
        </div>
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-warm-200 bg-warm-50">
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Usuario</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Experiencia</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Estado</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestOrders as $order)
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#{{ $order->id }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $order->user->name ?? 'Usuario' }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $order->experience->name ?? 'Experiencia' }}</td>
                    <td class="py-4 px-6">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-green-100 text-green-800">
                            {{ ucfirst($order->status ?? 'Completado') }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm text-warm-600">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-warm-500 text-sm">No hay reservaciones registradas aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const chartColor = {
        primary: '#D97706',
        secondary: '#F97316',
        success: '#10B981',
    };

    // Datos reales inyectados desde Laravel
    const chartMonths = @json($chartMonths);
    const monthlyData = @json($monthlyData);
    const expLabels = @json($expLabels);
    const expData = @json($expData);

    // Gráfica: Tendencia de Reservas Real
    const ctxVentas = document.getElementById('chartVentas').getContext('2d');
    new Chart(ctxVentas, {
        type: 'line',
        data: {
            labels: chartMonths,
            datasets: [{
                label: 'Reservas Reales',
                data: monthlyData,
                borderColor: chartColor.primary,
                backgroundColor: 'rgba(217, 119, 6, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Gráfica: Experiencias Registradas Reales
    const ctxExp = document.getElementById('chartExperiencias').getContext('2d');
    new Chart(ctxExp, {
        type: 'bar',
        data: {
            labels: expLabels,
            datasets: [{
                label: 'Reservas por Experiencia',
                data: expData,
                backgroundColor: ['#D97706', '#F97316', '#10B981', '#3B82F6', '#8B5CF6'],
                borderRadius: 8
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endsection