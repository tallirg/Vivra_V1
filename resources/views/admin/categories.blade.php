@extends('admin.layout')

@section('title', 'Categorías')
@section('header', 'Gestión de Categorías')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-warm-800">Categorías</h2>
        <button onclick="openModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nueva Categoría
        </button>
    </div>

    <div class="card">
        <table class="w-full">
            <thead class="bg-warm-50 border-b-2 border-warm-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Nombre</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Descripción</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#{{ $category->id }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $category->name }}</td>
                    <td class="py-4 px-6 text-sm text-warm-600">{{ $category->description }}</td>
                    <td class="py-4 px-6 text-sm space-x-2 flex">
                        <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                        <form action="/admin/categories/{{ $category->id }}" method="POST" style="display:inline;">
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

<div id="modalCategory" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-warm-50 rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 flex justify-between items-center rounded-t-lg">
            <h3 class="text-lg font-bold" id="modalTitle">Nueva Categoría</h3>
            <button onclick="closeModal()" class="text-2xl">&times;</button>
        </div>
        <form id="formCategory" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            <input type="text" id="name" name="name" placeholder="Nombre" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
            <textarea id="description" name="description" placeholder="Descripción" rows="3" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500"></textarea>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 btn-primary">Guardar</button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-warm-300 text-warm-800 py-2 rounded-lg font-bold">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalTitle').textContent = 'Nueva Categoría';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('formCategory').action = '/admin/categories';
        document.getElementById('name').value = '';
        document.getElementById('description').value = '';
        document.getElementById('modalCategory').classList.remove('hidden');
    }

    function editCategory(id, name, description) {
        document.getElementById('modalTitle').textContent = 'Editar Categoría';
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('formCategory').action = `/admin/categories/${id}`;
        document.getElementById('name').value = name;
        document.getElementById('description').value = description;
        document.getElementById('modalCategory').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalCategory').classList.add('hidden');
    }
</script>
@endsection
  
