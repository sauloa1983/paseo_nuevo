@php
    $ciudadesOferta = ($ciudadesPopulares ?? collect())->shuffle()->take(4);
@endphp

<section class="pe-city-offer">
    <div class="container">
        <div class="pe-city-offer__header">
            <h3 class="headline centered margin-bottom-35 margin-top-10">
                Explora nuestras zonas destacadas <span>Conoce las mejores opciones en las zonas más exclusivas</span>
            </h3>
        </div>

        @if($ciudadesOferta->isNotEmpty())
            <div class="pe-city-grid">
                @foreach($ciudadesOferta as $ciudadItem)
                    @php
                        $ciudad = $ciudadItem->ciudadRelacion ?? null;
                        $nombre = $ciudad->nombre ?? 'Nombre no disponible';
                        $imagenPath = filled($ciudad?->imagen)
                            ? asset('storage/' . ltrim($ciudad->imagen, '/'))
                            : asset('images/home-parallax.jpg');
                        $searchUrl = route('inmuebles.search', ['ciudad' => $ciudadItem->ciudad]);
                    @endphp

                    <a href="{{ $searchUrl }}" class="pe-city-card">
                        <img
                            src="{{ $imagenPath }}"
                            alt="{{ $nombre }}"
                            class="pe-city-card__image"
                            loading="lazy"
                        >
                        <div class="pe-city-card__overlay" aria-hidden="true"></div>
                        <div class="pe-city-card__content">
                            <h4 class="pe-city-card__title">{{ $nombre }}</h4>
                            <span class="pe-city-card__cta">Ver propiedades →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
