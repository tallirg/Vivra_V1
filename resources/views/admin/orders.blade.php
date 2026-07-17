@extends('admin.layout')

@section('title', 'Pedidos')
@section('header', 'Gestión de Pedidos')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-warm-800">Pedidos</h2>
        <div class="text-sm space-x-2">
            <span class="badge-success">Completados: 450</span>
            <span class="badge-confirmed">Confirmados: 250</span>
            <span class="badge-pending">Pendientes: 150</span>
        </div>
    </div>

    <div class="card">
        <div class="p-4 flex gap-4 border-b border-warm-200">
            <input type="text" placeholder="Buscar pedido..." class="flex-1 px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500 bg-warm-50">
            <select class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500 bg-warm-50">
                <option>Todos los estados</option>
                <option>Completado</option>
                <option>Confirmado</option>
                <option>Pendiente</option>
                <option>Cancelado</option>
            </select>
            <button class="btn-primary">Filtrar</button>
        </div>

        <table class="w-full">
            <thead class="bg-warm-50 border-b-2 border-warm-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">ID Pedido</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Usuario</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Experiencia</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Monto</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Estado</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#1001</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Juan García</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Tour Oaxaca Premium</td>
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">$700.00</td>
                    <td class="py-4 px-6"><span class="badge-success">Completado</span></td>
                    <td class="py-4 px-6"><button onclick="viewOrder()" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button></td>
                </tr>
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#1002</td>
                    <td class="py-4 px-6 text-sm text-warm-700">María López</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Clase de Cocina</td>
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">$180.00</td>
                    <td class="py-4 px-6"><span class="badge-confirmed">Confirmado</span></td>
                    <td class="py-4 px-6"><button onclick="cancelOrder()" class="text-red-600 hover:text-red-800"><i class="fas fa-times"></i></button></td>
                </tr>
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#1003</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Carlos Méndez</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Senderismo</td>
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">$360.00</td>
                    <td class="py-4 px-6"><span class="badge-pending">Pendiente</span></td>
                    <td class="py-4 px-6"><button onclick="cancelOrder()" class="text-red-600 hover:text-red-800"><i class="fas fa-times"></i></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function viewOrder() { alert('Ver detalles del pedido'); }
    function cancelOrder() { if(confirm('¿Cancelar pedido?')) alert('Pedido cancelado'); }
</script>
@endsection