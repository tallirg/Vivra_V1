@extends('admin.layout')

@section('title', 'Experiencias')
@section('header', 'Gestión de Experiencias')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-warm-800">Experiencias</h2>
        <button onclick="openModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nueva Experiencia
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
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Capacidad</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Estado</th>
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
                    <td class="py-4 px-6 text-sm">
                        @if($article->active)
                            <span class="text-green-600 font-bold">Activa</span>
                        @else
                            <span class="text-orange-500 font-bold">Pendiente</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm space-x-2 flex items-center">
                        <button onclick="editArticle({{ $article->id }}, '{{ addslashes($article->name) }}', {{ $article->price }}, {{ $article->stock }}, '{{ addslashes($article->description) }}', {{ $article->category_id }}, {{ $article->brand_id }})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                        
                        <form action="/admin/articles/{{ $article->id }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="active" value="{{ $article->active ? 0 : 1 }}">
                            <button type="submit" class="{{ $article->active ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }}" title="{{ $article->active ? 'Desactivar' : 'Aprobar Experiencia' }}">
                                <i class="fas {{ $article->active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                            </button>
                        </form>

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

<!-- Modal Formulario -->
<div id="modalArticle" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-warm-50 rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 flex justify-between items-center rounded-t-lg">
            <h3 class="text-lg font-bold" id="modalTitle">Nueva Experiencia</h3>
            <button onclick="closeModal()" class="text-2xl">&times;</button>
        </div>
        <form id="formArticle" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="articleId" name="id">
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-warm-700 mb-1">Nombre de la Experiencia</label>
                    <input type="text" id="name" name="name" placeholder="Ej. Ruta Gastronómica" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-warm-700 mb-1">Precio (MXN)</label>
                    <input type="number" id="price" name="price" step="0.01" placeholder="Precio" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-warm-700 mb-1">Categoría</label>
                    <select id="category_id" name="category_id" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
                        <option value="">Seleccione Categoría</option>
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-warm-700 mb-1">Prestador Local</label>
                    <select id="brand_id" name="brand_id" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
                        <option value="">Seleccione Prestador</option>
                        @foreach(\App\Models\Brand::all() as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-warm-700 mb-1">Capacidad / Cupo Máximo</label>
                <input type="number" id="stock" name="stock" placeholder="Cantidad de personas" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-warm-700 mb-1">Descripción de la Actividad</label>
                <textarea id="description" name="description" placeholder="Detalles de la experiencia artesanal o gastronómica..." class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" rows="3" required></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 btn-primary">Guardar</button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-warm-300 text-warm-800 py-2 rounded-lg font-bold">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalTitle').textContent = 'Nueva Experiencia';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('formArticle').action = '/admin/articles';
        document.getElementById('articleId').value = '';
        document.getElementById('name').value = '';
        document.getElementById('price').value = '';
        document.getElementById('stock').value = '';
        document.getElementById('description').value = '';
        document.getElementById('category_id').value = '';
        document.getElementById('brand_id').value = '';
        document.getElementById('modalArticle').classList.remove('hidden');
    }

    function editArticle(id, name, price, stock, description, categoryId, brandId) {
        document.getElementById('modalTitle').textContent = 'Editar Experiencia';
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('formArticle').action = `/admin/articles/${id}`;
        document.getElementById('articleId').value = id;
        document.getElementById('name').value = name;
        document.getElementById('price').value = price;
        document.getElementById('stock').value = stock;
        document.getElementById('description').value = description;
        document.getElementById('category_id').value = categoryId;
        document.getElementById('brand_id').value = brandId;
        document.getElementById('modalArticle').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalArticle').classList.add('hidden');
    }
</script>
@endsection
