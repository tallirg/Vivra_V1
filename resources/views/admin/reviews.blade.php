@extends('admin.layout')

@section('title', 'Reseñas')
@section('header', 'Gestión de Reseñas')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-warm-800">Reseñas y Calificaciones</h2>

    <div class="space-y-4">
        @foreach($reviews as $review)
        <div class="card border-l-4 {{ $review->approved ? 'border-l-green-500' : 'border-l-yellow-400' }} p-6">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-warm-800">{{ $review->user->name ?? 'Usuario' }}</p>
                        <span class="{{ $review->approved ? 'badge-success' : 'badge-pending' }}">{{ $review->approved ? 'Aprobada' : 'Pendiente' }}</span>
                    </div>
                    <p class="text-sm text-warm-600 mt-1">{{ $review->experience->title ?? 'Experiencia' }}</p>
                </div>
                <div class="flex gap-1 text-yellow-400">
                    @for($i = 0; $i < $review->rating; $i++)
                        <i class="fas fa-star"></i>
                    @endfor
                    <span class="text-warm-600 text-sm ml-2">({{ $review->rating }}/5)</span>
                </div>
            </div>
            <p class="text-warm-700 mb-3">{{ $review->comment }}</p>
            <div class="flex justify-between items-center text-sm text-warm-600">
                <span>{{ $review->created_at->format('d/m/Y') }}</span>
                <div class="space-x-3">
                    @if(!$review->approved)
                    <button onclick="approveReview({{ $review->id }})" class="text-green-600 hover:text-green-800"><i class="fas fa-check"></i> Aprobar</button>
                    @endif
                    <form action="/admin/reviews/{{ $review->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:text-red-800" onclick="return confirm('¿Eliminar?')"><i class="fas fa-trash"></i> Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function approveReview(id) {
        if(confirm('¿Aprobar reseña?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/reviews/${id}`;
            form.innerHTML = '@csrf @method("PUT")<input type="hidden" name="approved" value="1">';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection