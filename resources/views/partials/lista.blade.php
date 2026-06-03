@php
    $layoutClass = match ($layout ?? 'grid-three') {
        'grid' => 'grid-layout',
        'grid-three' => 'grid-layout-three',
        default => 'list-layout',
    };
@endphp
<div id="listings-container" class="listings-container {{ $layoutClass }} pe-listings-modern">
    @forelse($properties as $property)
        @include('partials.inmueble-card', [
            'inmueble' => $property,
            'modo' => 'auto',
            'showAsesor' => true,
        ])
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center" style="padding: 100px 0;">
            <span class="im im-icon-Search-onCloud ico-big"></span>
            <h1>No encontramos lo que buscas</h1>
            <p>Lo sentimos, no hay resultados que coincidan con tu búsqueda. Intenta ajustar los filtros.</p>
        </div>
    </div>
    @endforelse
</div>
<div class="clearfix"></div>

@if(isset($properties) && $properties->hasPages())
    <div class="pagination-container margin-top-20">
        <nav class="pagination">
            <ul>
                @php
                    $current = $properties->currentPage();
                    $maxVisible = 3;

                    $start = max(1, $current - 2);
                    $end = min($properties->lastPage(), $start + $maxVisible - 1);

                    if($end - $start + 1 < $maxVisible) {
                        $start = max(1, $end - $maxVisible + 1);
                    }
                @endphp

                @for($i = $start; $i <= $end; $i++)
                    @if($i == $properties->currentPage())
                        <li><span class="current-page">{{ $i }}</span></li>
                    @else
                        <li><a href="{{ $properties->appends(request()->query())->url($i) }}" class="page-link">{{ $i }}</a></li>
                    @endif
                @endfor

                @if($end < $properties->lastPage())
                    <li class="blank">...</li>
                    @for($i = max(1, $properties->lastPage() - 1); $i <= $properties->lastPage(); $i++)
                        @if($i != $properties->currentPage())
                            <li><a href="{{ $properties->appends(request()->query())->url($i) }}" class="page-link">{{ $i }}</a></li>
                        @endif
                    @endfor
                @endif
            </ul>
        </nav>

        <nav class="pagination-next-prev">
            <ul>
                @if($properties->onFirstPage())
                    <li><a href="#" class="prev disabled">Anterior</a></li>
                @else
                    <li><a href="{{ $properties->appends(request()->query())->previousPageUrl() }}" class="prev">Anterior</a></li>
                @endif

                @if($properties->hasMorePages())
                    <li><a href="{{ $properties->appends(request()->query())->nextPageUrl() }}" class="next">Siguiente</a></li>
                @else
                    <li><a href="#" class="next disabled">Siguiente</a></li>
                @endif
            </ul>
        </nav>

    </div>
@endif
