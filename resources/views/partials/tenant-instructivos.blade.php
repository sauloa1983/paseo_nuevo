@php
    $instructivos = config('tenant_instructivos', []);
@endphp

@if (count($instructivos) > 0)
    <section class="pe-instructivos" aria-labelledby="tenant-instructivos-heading">
        <div class="container">
            <p id="tenant-instructivos-heading" class="titulo-negro-secciones">Instructivos y manuales</p>
            <span class="pe-why-choose__line" aria-hidden="true"></span>

            <div class="pe-instructivos__grid">
                @foreach ($instructivos as $item)
                    @php
                        $href = tenant_instructivo_url($item);
                        $isExternal = ! empty($item['url']);
                        $alt = $item['title'] ?? 'Instructivo';
                    @endphp
                    @if ($href)
                        <a
                            href="{{ $href }}"
                            class="pe-instructivos-card"
                            @if ($isExternal) target="_blank" rel="noopener noreferrer" @endif
                        >
                            <img
                                src="{{ asset($item['image'] ?? 'images/inmueble.jpg') }}"
                                alt="{{ $alt }}"
                                loading="lazy"
                                decoding="async"
                            >
                        </a>
                    @else
                        <div class="pe-instructivos-card">
                            <img
                                src="{{ asset($item['image'] ?? 'images/inmueble.jpg') }}"
                                alt="{{ $alt }}"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif
