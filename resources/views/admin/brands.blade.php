@extends('admin.layout')

@section('title', 'Marcas')
@section('header', 'Gestión de Marcas')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-warm-800">Marcas</h2>
        <button onclick="openModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nueva Marca
        </button>
    </div>

    <div class="card">
        <div class="p-4 flex gap-4 border-b border-warm-200">
            <input type="text" placeholder="Buscar marcas..." class="flex-1 px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500 bg-warm-50">
            <button class="btn-primary">Filtrar</button>
        </div>

      <table class="w-full">
    <thead class="bg-warm-50 border-b-2 border-warm-200">
        <tr>
            <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">ID</th>
            <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Nombre</th>
            <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Descripción</th>
            <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Estado</th>
            <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($brands as $brand)
        <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
            <td class="py-4 px-6 text-sm font-bold text-warm-800">#{{ $brand->id }}</td>
            <td class="py-4 px-6 text-sm text-warm-700">{{ $brand->name }}</td>
            <td class="py-4 px-6 text-sm text-warm-600">{{ $brand->description }}</td>
            <td class="py-4 px-6"><span class="badge-success">{{ $brand->active ? 'Activa' : 'Inactiva' }}</span></td>
            <td class="py-4 px-6 text-sm space-x-2 flex">
                <a href="/admin/brands/{{ $brand->id }}/edit" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></a>
                <form action="/admin/brands/{{ $brand->id }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:text-red-800" onclick="return confirm('¿Eliminar?')"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    </div>
</div>

<div id="modalBrand" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-warm-50 rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 flex justify-between items-center rounded-t-lg">
            <h3 class="text-lg font-bold">Nueva Marca</h3>
            <button onclick="closeModal()" class="text-2xl">&times;</button>
        </div>
        <form class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <input type="text" placeholder="Nombre de la marca" class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500">
                <input type="file" placeholder="Logo" class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500">
            </div>
            <textarea placeholder="Descripción" rows="3" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500"></textarea>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 btn-primary">Guardar</button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-warm-300 text-warm-800 py-2 rounded-lg font-bold">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('modalBrand').classList.remove('hidden'); }
    function closeModal() { document.getElementById('modalBrand').classList.add('hidden'); }
    function editBrand() { openModal(); }
    function deleteBrand() { if(confirm('¿Eliminar?')) alert('Eliminada'); }
</script>
@endsection