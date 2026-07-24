@extends('admin.layout')

@section('title', 'Reservaciones')
@section('header', 'Control de Reservaciones')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-warm-800">Reservaciones</h2>

    <div class="card">
        <table class="w-full">
            <thead class="bg-warm-50 border-b-2 border-warm-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Usuario</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Experiencia</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Total Pagado</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Estado</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#{{ $order->id }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $order->user->name ?? 'N/A' }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $order->experience->name ?? 'N/A' }}</td>
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">${{ $order->total_price }}</td>
                    <td class="py-4 px-6">
                        @if($order->status == 'completed')
                            <span class="badge-success">Completado</span>
                        @elseif($order->status == 'confirmed')
                            <span class="badge-confirmed">Confirmado</span>
                        @elseif($order->status == 'pending')
                            <span class="badge-pending">Pendiente</span>
                        @else
                            <span class="badge-danger">Cancelado</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm space-x-2 flex">
                        <button onclick="changeStatus({{ $order->id }}, '{{ $order->status }}')" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                        <form action="/admin/orders/{{ $order->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar registro" onclick="return confirm('¿Estás seguro de eliminar permanentemente esta reservación?')">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modalStatus" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-warm-50 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 flex justify-between items-center rounded-t-lg">
            <h3 class="text-lg font-bold">Cambiar Estado</h3>
            <button onclick="closeModal()" class="text-2xl">&times;</button>
        </div>
        <form id="formStatus" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <select id="status" name="status" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500">
                <option value="pending">Pendiente</option>
                <option value="confirmed">Confirmado</option>
                <option value="completed">Completado</option>
                <option value="cancelled">Cancelado</option>
            </select>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 btn-primary">Guardar</button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-warm-300 text-warm-800 py-2 rounded-lg font-bold">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .badge-danger {
        background: linear-gradient(135deg, #FEE2E2 0%, #FCA5A5 100%);
        color: #7F1D1D;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
</style>

<script>
    function changeStatus(id, status) {
        document.getElementById('formStatus').action = `/admin/orders/${id}`;
        document.getElementById('status').value = status;
        document.getElementById('modalStatus').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalStatus').classList.add('hidden');
    }
</script>
@endsection