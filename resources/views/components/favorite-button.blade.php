@props(['dealership', 'isFull' => false])

@php
    $isFavorite = auth()->check() && auth()->user()->favoriteDealerships->contains($dealership->id);
@endphp

<button 
    onclick="toggleFavorite(event, {{ $dealership->id }}, '{{ route('dealerships.favorite', $dealership) }}')"
    data-dealership-id="{{ $dealership->id }}"
    class="favorite-btn group flex items-center justify-center transition-all duration-300 {{ $isFull ? 'px-6 py-3 rounded-full border shadow-sm' : 'p-2 rounded-xl' }} {{ $isFavorite ? 'bg-red-50 border-red-100 text-red-500' : 'bg-white border-gray-100 text-gray-400 hover:text-red-500' }}"
>
    <svg class="h-6 w-6 {{ $isFavorite ? 'fill-current' : 'fill-none' }} stroke-current" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
    @if($isFull)
        <span class="ml-2 font-bold">{{ $isFavorite ? 'В избранном' : 'В избранное' }}</span>
    @endif
</button>

<script>
    if (typeof toggleFavorite === 'undefined') {
        function toggleFavorite(event, id, url) {
            event.preventDefault();
            
            @guest
                window.location.href = "{{ route('login') }}";
                return;
            @endguest

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const buttons = document.querySelectorAll(`[data-dealership-id="${id}"]`);
                buttons.forEach(btn => {
                    const svg = btn.querySelector('svg');
                    const span = btn.querySelector('span');
                    
                    if (data.status === 'added') {
                        btn.classList.remove('bg-white', 'border-gray-100', 'text-gray-400');
                        btn.classList.add('bg-red-50', 'border-red-100', 'text-red-500');
                        svg.classList.add('fill-current');
                        svg.classList.remove('fill-none');
                        if (span) span.innerText = 'В избранном';
                    } else {
                        btn.classList.add('bg-white', 'border-gray-100', 'text-gray-400');
                        btn.classList.remove('bg-red-50', 'border-red-100', 'text-red-500');
                        svg.classList.remove('fill-current');
                        svg.classList.add('fill-none');
                        if (span) span.innerText = 'В избранное';
                    }
                });
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
