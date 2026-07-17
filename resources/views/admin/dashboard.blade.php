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
                    <p class="text-4xl font-bold text-warm-800 mt-3">1,245</p>
                </div>
                <i class="fas fa-users text-orange-400 text-4xl opacity-30"></i>
            </div>
            <p class="text-green-600 text-sm mt-4 font-semibold">↑ 12% este mes</p>
        </div>

        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-warm-600 text-sm font-semibold uppercase tracking-wider">Total Experiencias</p>
                    <p class="text-4xl font-bold text-warm-800 mt-3">456</p>
                </div>
                <i class="fas fa-map-pin text-blue-400 text-4xl opacity-30"></i>
            </div>
            <p class="text-green-600 text-sm mt-4 font-semibold">↑ 8% este mes</p>
        </div>

        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-warm-600 text-sm font-semibold uppercase tracking-wider">Pedidos Totales</p>
                    <p class="text-4xl font-bold text-warm-800 mt-3">892</p>
                </div>
                <i class="fas fa-shopping-cart text-orange-500 text-4xl opacity-30"></i>
            </div>
            <p class="text-green-600 text-sm mt-4 font-semibold">↑ 25% este mes</p>
        </div>

        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-warm-600 text-sm font-semibold uppercase tracking-wider">Ingresos</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent mt-3">$45,620</p>
                </div>
                <i class="fas fa-dollar-sign text-yellow-500 text-4xl opacity-30"></i>
            </div>
            <p class="text-green-600 text-sm mt-4 font-semibold">↑ 18% este mes</p>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfica: Ventas por Mes -->
        <div class="chart-container">
            <h3 class="text-lg font-bold text-warm-800 mb-6">Tendencia de Ventas</h3>
            <div style="position: relative; height: 250px;">
                <canvas id="chartVentas"></canvas>
            </div>
        </div>

        <!-- Gráfica: Experiencias Populares -->
        <div class="chart-container">
            <h3 class="text-lg font-bold text-warm-800 mb-6">Experiencias Más Populares</h3>
            <div style="position: relative; height: 250px;">
                <canvas id="chartExperiencias"></canvas>
            </div>
        </div>

        <!-- Gráfica: Calificaciones -->
        <div class="chart-container">
            <h3 class="text-lg font-bold text-warm-800 mb-6">Distribución de Calificaciones</h3>
            <div style="position: relative; height: 250px;">
                <canvas id="chartCalificaciones"></canvas>
            </div>
        </div>

        <!-- Gráfica: Estado de Pedidos -->
        <div class="chart-container">
            <h3 class="text-lg font-bold text-warm-800 mb-6">Estado de Pedidos</h3>
            <div style="position: relative; height: 250px;">
                <canvas id="chartPedidos"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabla: Últimas Actividades -->
    <div class="card">
        <div class="p-6 border-b-2 border-warm-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-warm-800">Últimos Pedidos</h3>
            <a href="#" class="text-orange-600 hover:text-orange-700 text-sm font-semibold">Ver todos →</a>
        </div>
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-warm-200 bg-warm-50">
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Usuario</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Experiencia</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Monto</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Estado</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Fecha</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#1001</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Juan García</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Tour Oaxaca</td>
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">$250.00</td>
                    <td class="py-4 px-6"><span class="badge-success">Completado</span></td>
                    <td class="py-4 px-6 text-sm text-warm-600">15/07/2026</td>
                </tr>
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#1002</td>
                    <td class="py-4 px-6 text-sm text-warm-700">María López</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Clase de Cocina</td>
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">$180.00</td>
                    <td class="py-4 px-6"><span class="badge-confirmed">Confirmado</span></td>
                    <td class="py-4 px-6 text-sm text-warm-600">15/07/2026</td>
                </tr>
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#1003</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Carlos Méndez</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Senderismo</td>
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">$120.00</td>
                    <td class="py-4 px-6"><span class="badge-pending">Pendiente</span></td>
                    <td class="py-4 px-6 text-sm text-warm-600">14/07/2026</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    const chartColor = {
        primary: '#D97706',
        secondary: '#F97316',
        success: '#10B981',
        warning: '#FBBF24',
        danger: '#EF4444'
    };

    // Gráfica: Ventas por Mes
    const ctxVentas = document.getElementById('chartVentas').getContext('2d');
    new Chart(ctxVentas, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Ventas ($)',
                data: [5200, 6100, 7300, 6800, 8200, 9100, 10500],
                borderColor: chartColor.primary,
                backgroundColor: 'rgba(217, 119, 6, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: chartColor.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                filler: { propagate: true }
            },
            scales: { 
                y: { 
                    beginAtZero: true,
                    grid: { color: 'rgba(217, 119, 6, 0.1)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Gráfica: Experiencias Populares
    const ctxExp = document.getElementById('chartExperiencias').getContext('2d');
    new Chart(ctxExp, {
        type: 'bar',
        data: {
            labels: ['Tour Oaxaca', 'Clase Cocina', 'Senderismo', 'Playa', 'Cultura'],
            datasets: [{
                label: 'Reservas',
                data: [65, 59, 80, 81, 56],
                backgroundColor: [
                    'rgba(217, 119, 6, 0.8)',
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });

    // Gráfica: Calificaciones
    const ctxCal = document.getElementById('chartCalificaciones').getContext('2d');
    new Chart(ctxCal, {
        type: 'doughnut',
        data: {
            labels: ['5 Estrellas', '4 Estrellas', '3 Estrellas', '2 Estrellas', '1 Estrella'],
            datasets: [{
                data: [45, 25, 15, 10, 5],
                backgroundColor: [
                    '#10B981',
                    '#3B82F6',
                    '#F97316',
                    '#FBBF24',
                    '#EF4444'
                ],
                borderColor: '#FFFBF0',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'right' },
                tooltip: { callbacks: { label: (context) => `${context.label}: ${context.parsed}` } }
            }
        }
    });

    // Gráfica: Estado de Pedidos
    const ctxPed = document.getElementById('chartPedidos').getContext('2d');
    new Chart(ctxPed, {
        type: 'pie',
        data: {
            labels: ['Completados', 'Confirmados', 'Pendientes', 'Cancelados'],
            datasets: [{
                data: [450, 250, 150, 42],
                backgroundColor: ['#10B981', '#3B82F6', '#FBBF24', '#EF4444'],
                borderColor: '#FFFBF0',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right' } }
        }
    });
</script>
@endsection