@extends('admin.layout')

@section('title', 'Artículos')
@section('header', 'Gestión de Artículos')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-warm-800">Artículos</h2>
        <button onclick="openModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Crear Artículo
        </button>
    </div>

    <div class="card">
        <table class="w-full">
            <thead class="bg-warm-50 border-b-2 border-warm-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Nombre</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Categoría</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Precio</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Stock</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#{{ $article->id }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $article->name }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $article->category->name ?? 'N/A' }}</td>
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">${{ $article->price }}</td>
                    <td class="py-4 px-6 text-sm text-warm-600">{{ $article->stock }}</td>
                    <td class="py-4 px-6 text-sm space-x-2 flex">
                        <button onclick="editArticle({{ $article->id }}, '{{ $article->name }}', {{ $article->price }}, {{ $article->stock }})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                        <form action="/admin/articles/{{ $article->id }}" method="POST" style="display:inline;">
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

<div id="modalArticle" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-warm-50 rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 flex justify-between items-center rounded-t-lg">
            <h3 class="text-lg font-bold" id="modalTitle">Crear Artículo</h3>
            <button onclick="closeModal()" class="text-2xl">&times;</button>
        </div>
        <form id="formArticle" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="articleId" name="id">
            <input type="hidden" id="methodField" name="_method" value="POST">
            <div class="grid grid-cols-2 gap-4">
                <input type="text" id="name" name="name" placeholder="Nombre" class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
                <input type="number" id="price" name="price" step="0.01" placeholder="Precio" class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
            </div>
            <input type="number" id="stock" name="stock" placeholder="Stock" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 btn-primary">Guardar</button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-warm-300 text-warm-800 py-2 rounded-lg font-bold">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalTitle').textContent = 'Crear Artículo';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('formArticle').action = '/admin/articles';
        document.getElementById('articleId').value = '';
        document.getElementById('name').value = '';
        document.getElementById('price').value = '';
        document.getElementById('stock').value = '';
        document.getElementById('modalArticle').classList.remove('hidden');
    }

    function editArticle(id, name, price, stock) {
        document.getElementById('modalTitle').textContent = 'Editar Artículo';
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('formArticle').action = `/admin/articles/${id}`;
        document.getElementById('articleId').value = id;
        document.getElementById('name').value = name;
        document.getElementById('price').value = price;
        document.getElementById('stock').value = stock;
        document.getElementById('modalArticle').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalArticle').classList.add('hidden');
    }
</script>
@endsection