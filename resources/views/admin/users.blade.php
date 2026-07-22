@extends('admin.layout')

@section('title', 'Usuarios')
@section('header', 'Gestión de Usuarios')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-warm-800">Usuarios Registrados</h2>
        <button onclick="openUserModal()" class="btn-primary">
            <i class="fas fa-user-plus mr-2"></i> Nuevo Usuario
        </button>
    </div>

    <div class="card">
        <!-- Formulario de Búsqueda y Filtros -->
        <form method="GET" action="{{ url('admin/users') }}" class="p-4 flex gap-4 border-b border-warm-200">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o email..." class="flex-1 px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500 bg-warm-50">
            
            <select name="role" class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500 bg-warm-50">
                <option value="Todos los roles" {{ request('role') == 'Todos los roles' ? 'selected' : '' }}>Todos los roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="provider" {{ request('role') == 'provider' ? 'selected' : '' }}>Prestador</option>
                <option value="tourist" {{ request('role') == 'tourist' ? 'selected' : '' }}>Turista</option>
            </select>
            
            <button type="submit" class="btn-primary">Filtrar</button>
            @if(request()->has('search') || request()->has('role'))
                <a href="{{ url('admin/users') }}" class="bg-warm-300 text-warm-800 px-4 py-2 rounded-lg font-bold flex items-center justify-center">Limpiar</a>
            @endif
        </form>

        <table class="w-full">
            <thead class="bg-warm-50 border-b-2 border-warm-200">
                <tr>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">ID</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Nombre</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Email</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Rol</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Estado</th>
                    <th class="text-left py-4 px-6 text-sm font-bold text-warm-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#{{ $user->id }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $user->name }}</td>
                    <td class="py-4 px-6 text-sm text-warm-700">{{ $user->email }}</td>
                    <td class="py-4 px-6">
                        @if($user->role == 'admin')
                            <span class="bg-purple-200 text-purple-800 text-xs px-3 py-1 rounded-full font-bold">Admin</span>
                        @elseif($user->role == 'provider')
                            <span class="bg-blue-200 text-blue-800 text-xs px-3 py-1 rounded-full font-bold">Prestador</span>
                        @else
                            <span class="bg-green-200 text-green-800 text-xs px-3 py-1 rounded-full font-bold">Turista</span>
                        @endif
                    </td>
                    <td class="py-4 px-6"><span class="text-green-600 font-bold text-xs bg-green-100 px-2 py-1 rounded">Activo</span></td>
                    <td class="py-4 px-6 text-sm space-x-3 flex items-center">
                        <form action="/admin/users/{{ $user->id }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-800" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-6 text-center text-warm-500 text-sm">No se encontraron usuarios coincidentes.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Crear Usuario -->
<div id="modalUser" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-warm-50 rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 flex justify-between items-center rounded-t-lg">
            <h3 class="text-lg font-bold">Crear Nuevo Usuario</h3>
            <button onclick="closeUserModal()" class="text-2xl">&times;</button>
        </div>
        <form method="POST" action="{{ url('admin/users') }}" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-warm-700 mb-1">Nombre Completo</label>
                    <input type="text" name="name" placeholder="Nombre Completo" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-warm-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" placeholder="Email" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-warm-700 mb-1">Rol del Sistema</label>
                    <select name="role" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
                        <option value="">Seleccionar rol...</option>
                        <option value="admin">Admin</option>
                        <option value="provider">Prestador</option>
                        <option value="tourist">Turista</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-warm-700 mb-1">Contraseña de Acceso</label>
                    <input type="password" name="password" placeholder="Mínimo 4 caracteres" class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500" required>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 btn-primary">Crear Usuario</button>
                <button type="button" onclick="closeUserModal()" class="flex-1 bg-warm-300 text-warm-800 py-2 rounded-lg font-bold">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openUserModal() { document.getElementById('modalUser').classList.remove('hidden'); }
    function closeUserModal() { document.getElementById('modalUser').classList.add('hidden'); }
</script>
@endsection
