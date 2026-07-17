@extends('admin.layout')

@section('title', 'Reseñas')
@section('header', 'Gestión de Reseñas')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-warm-800">Reseñas y Calificaciones</h2>
        <div class="text-sm space-x-2">
            <span class="badge-success">Aprobadas: 324</span>
            <span class="badge-pending">Pendientes: 18</span>
        </div>
    </div>

    <div class="card">
        <div class="p-4 flex gap-4 border-b border-warm-200">
            <input type="text" placeholder="Buscar reseña..." class="flex-1 px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500 bg-warm-50">
            <select class="px-4 py-2 border border-warm-300 rounded-lg focus:outline-none focus:border-orange-500 bg-warm-50">
                <option>Todas las calificaciones</option>
                <option>5 Estrellas</option>
                <option>4 Estrellas</option>
                <option>3 Estrellas</option>
            </select>
            <button class="btn-primary">Filtrar</button>
        </div>
    </div>

    <!-- Reseñas -->
    <div class="space-y-4">
        <div class="card border-l-4 border-l-green-500 p-6">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-warm-800">María López González</p>
                        <span class="badge-success">Aprobada</span>
                    </div>
                    <p class="text-sm text-warm-600 mt-1">Tour Oaxaca Premium</p>
                </div>
                <div class="flex gap-1 text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="text-warm-600 text-sm ml-2">(5/5)</span>
                </div>
            </div>
            <p class="text-warm-700 mb-3">"Una experiencia increíble. El tour fue bien organizado, el guía fue muy amable y profesional."</p>
            <div class="flex justify-between items-center text-sm text-warm-600">
                <span>10/07/2026</span>
                <button onclick="deleteReview()" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i> Eliminar</button>
            </div>
        </div>

        <div class="card border-l-4 border-l-yellow-400 p-6">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-warm-800">Juan García López</p>
                        <span class="badge-pending">Pendiente</span>
                    </div>
                    <p class="text-sm text-warm-600 mt-1">Clase de Cocina Oaxaca</p>
                </div>
                <div class="flex gap-1 text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                    <span class="text-warm-600 text-sm ml-2">(4/5)</span>
                </div>
            </div>
            <p class="text-warm-700 mb-3">"Buena experiencia. El chef fue paciente, pero podría ser más larga para el precio."</p>
            <div class="flex justify-between items-center text-sm text-warm-600">
                <span>12/07/2026</span>
                <div class="space-x-3">
                    <button onclick="approveReview()" class="text-green-600 hover:text-green-800"><i class="fas fa-check"></i> Aprobar</button>
                    <button onclick="rejectReview()" class="text-red-600 hover:text-red-800"><i class="fas fa-times"></i> Rechazar</button>
                </div>
            </div>
        </div>

        <div class="card border-l-4 border-l-green-500 p-6">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-warm-800">Ana Rodríguez Morales</p>
                        <span class="badge-success">Aprobada</span>
                    </div>
                    <p class="text-sm text-warm-600 mt-1">Senderismo en Montaña</p>
                </div>
                <div class="flex gap-1 text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="text-warm-600 text-sm ml-2">(5/5)</span>
                </div>
            </div>
            <p class="text-warm-700 mb-3">"Senderismo perfecto para todos los niveles. Las vistas desde la cima fueron hermosas. Definitivamente volveré."</p>
            <div class="flex justify-between items-center text-sm text-warm-600">
                <span>13/07/2026</span>
                <button onclick="deleteReview()" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function approveReview() { if(confirm('¿Aprobar reseña?')) alert('Aprobada'); }
    function rejectReview() { if(confirm('¿Rechazar reseña?')) alert('Rechazada'); }
    function deleteReview() { if(confirm('¿Eliminar reseña?')) alert('Eliminada'); }
</script>
@endsection
