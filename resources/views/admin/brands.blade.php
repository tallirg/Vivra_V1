@extends('admin.layout')

@section('title', 'Prestadores')
@section('header', 'Gestión de Prestadores')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-warm-800">Prestadores Locales</h2>
        <!-- Cambiamos el texto para dejar claro que crearemos un usuario prestador -->
        <button onclick="openModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nuevo Prestador
        </button>
    </div>

    <div class="card">
        <table class="w-full">
            <thead class="bg-warm-50 border-b-2 border-warm-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Nombre</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Email</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- CAMBIO 1: Iteramos $prestadores en lugar de $brands --}}
                @foreach($prestadores as $prestador)
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#{{ $prestador->id }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $prestador->name }}</td>
                    {{-- CAMBIO 2: Mostramos $prestador->email en lugar de description --}}
                    <td class="py-4 px-6 text-sm text-warm-600">{{ $prestador->email }}</td>
                    <td class="py-4 px-6 text-sm space-x-2 flex">
                        <button onclick="editBrand({{ $prestador->id }}, '{{ $prestador->name }}', '{{ $prestador->email }}')" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                        
                        {{-- CAMBIO 3: La ruta para eliminar ahora apunta al usuario --}}
                        <form action="/admin/brands/{{ $prestador->id }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-800" onclick="return confirm('¿Deseas eliminar este prestador?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Crear / Editar Prestador -->
<div id="modalBrand" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-warm-50 rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 flex justify-between items-center rounded-t-lg">
            <h3 class="text-lg font-bold" id="modalTitle">Nuevo Prestador</h3>
            <button onclick="closeModal()" class="text-2xl">&times;</button>
        </div>
        <form id="formBrand" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <div>
                <label class="block text-sm font-medium text-warm-700 mb-1">Nombre</label>
                <input type="text" id="name" name="name" placeholder="Nombre completo" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-warm-700 mb-1">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
            </div>

            {{-- Campo para contraseña sólo al crear nuevo --}}
            <div id="passwordField">
                <label class="block text-sm font-medium text-warm-700 mb-1">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500">
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
        document.getElementById('modalTitle').textContent = 'Nuevo Prestador';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('formBrand').action = '/admin/brands';
        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
        document.getElementById('passwordField').classList.remove('hidden');
        document.getElementById('modalBrand').classList.remove('hidden');
    }

    function editBrand(id, name, email) {
        document.getElementById('modalTitle').textContent = 'Editar Prestador';
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('formBrand').action = `/admin/brands/${id}`;
        document.getElementById('name').value = name;
        document.getElementById('email').value = email;
        document.getElementById('passwordField').classList.add('hidden'); // Ocultar contraseña en edición
        document.getElementById('modalBrand').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalBrand').classList.add('hidden');
    }
</script>
@endsection