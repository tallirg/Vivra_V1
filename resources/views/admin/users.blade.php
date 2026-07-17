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
        <div class="p-4 flex gap-4 border-b border-warm-200">
            <input type="text" placeholder="Buscar usuario..." class="flex-1 px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500 bg-warm-50">
            <select class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500 bg-warm-50">
                <option>Todos los roles</option>
                <option>Admin</option>
                <option>Prestador</option>
                <option>Turista</option>
            </select>
            <button class="btn-primary">Filtrar</button>
        </div>

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
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#U001</td>
                    <td class="py-4 px-6 text-sm text-warm-700">Juan García López</td>
                    <td class="py-4 px-6 text-sm text-warm-700">juan@example.com</td>
                    <td class="py-4 px-6"><span class="bg-purple-200 text-purple-800 text-xs px-3 py-1 rounded-full font-bold">Admin</span></td>
                    <td class="py-4 px-6"><span class="badge-success">Activo</span></td>
                    <td class="py-4 px-6 text-sm space-x-3 flex">
                        <button onclick="editUser()" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                        <button onclick="disableUser()" class="text-red-600 hover:text-red-800"><i class="fas fa-ban"></i></button>
                    </td>
                </tr>
                <tr class="border-b border-warm-100 hover:bg-warm-50 transition">
                    <td class="py-4 px-6 text-sm font-bold text-warm-800">#U002</td>
                    <td class="py-4 px-6 text-sm text-warm-700">María López González</td>
                    <td class="py-4 px-6 text-sm text-warm-700">maria@example.com</td>
                    <td class="py-4 px-6"><span class="bg-blue-200 text-blue-800 text-xs px-3 py-1 rounded-full font-bold">Prestador</span></td>
                    <td class="py-4 px-6"><span class="badge-success">Activo</span></td>
                    <td class="py-4 px-6 text-sm space-x-3 flex">
                        <button onclick="editUser()" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                        <button onclick="disableUser()" class="text-red-600 hover:text-red-800"><i class="fas fa-ban"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="modalUser" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-warm-50 rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 flex justify-between items-center rounded-t-lg">
            <h3 class="text-lg font-bold">Crear Nuevo Usuario</h3>
            <button onclick="closeUserModal()" class="text-2xl">&times;</button>
        </div>
        <form class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <input type="text" placeholder="Nombre Completo" class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500">
                <input type="email" placeholder="Email" class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500">
            </div>
            <select class="w-full px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500">
                <option>Seleccionar rol...</option>
                <option>Admin</option>
                <option>Prestador</option>
                <option>Turista</option>
            </select>
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
    function editUser() { openUserModal(); }
    function disableUser() { if(confirm('¿Deshabilitar usuario?')) alert('Deshabilitado'); }
</script>
@endsection