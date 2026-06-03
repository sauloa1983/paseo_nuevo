@php
    $showUrl = route('inmuebles.show', $inmueble->codigo);
    $esNuevo = $inmueble->fecha_captacion >= now()->subDays(30);

    $fotoUrl = null;
    if (! empty($inmueble->fotoInmueble) && $inmueble->fotoInmueble->isNotEmpty()) {
        $fotoUrl = 'https://www.paseoespanainmobiliaria.com/' . ltrim($inmueble->fotoInmueble->first()->foto, '/');
    } elseif (! empty($inmueble->fotos) && $inmueble->fotos->isNotEmpty()) {
        $fotoUrl = 'https://www.paseoespanainmobiliaria.com/' . ltrim($inmueble->fotos->first()->foto, '/');
    }

    // Sufijo "+ IVA" cuando el inmueble está marcado con IVA (locales, oficinas, etc.).
    // El "+ IVA" NO se aplica al precio de venta.
    $ivaSuffix = ! empty($inmueble->iva) ? ' + IVA' : '';

    $precioSecundario = null;
    $ivaPrincipal = '';
    if (! isset($precio)) {
        $modoPrecio = $modo ?? 'auto';

        if ($modoPrecio === 'arriendo') {
            $precio = '$' . number_format($inmueble->valor_arriendo, 0, ',', '.');
            $ivaPrincipal = $ivaSuffix;
        } elseif ($modoPrecio === 'venta') {
            $precio = '$' . number_format($inmueble->valor_venta, 0, ',', '.');
        } else {
            if ($inmueble->arriendo && $inmueble->venta) {
                $precio = '$' . number_format($inmueble->valor_arriendo, 0, ',', '.');
                $precioSecundario = '$' . number_format($inmueble->valor_venta, 0, ',', '.');
                $ivaPrincipal = $ivaSuffix;
            } elseif ($inmueble->arriendo) {
                $precio = '$' . number_format($inmueble->valor_arriendo, 0, ',', '.');
                $ivaPrincipal = $ivaSuffix;
            } elseif ($inmueble->venta) {
                $precio = '$' . number_format($inmueble->valor_venta, 0, ',', '.');
            } else {
                $precio = 'Consultar';
            }
        }
    }

    $ciudadNombre = optional($inmueble->ciudadRelacion)->nombre
        ?? optional($inmueble->ciudad)->nombre
        ?? 'No asignada';

    $alcobasTexto = empty($inmueble->no_alcobas)
        ? 'N/A'
        : $inmueble->no_alcobas . ' ' . ((int) $inmueble->no_alcobas === 1 ? 'alcoba' : 'alcobas');

    $parqueaderosTexto = empty($inmueble->garajes)
        ? 'N/A'
        : $inmueble->garajes . ' ' . ((int) $inmueble->garajes === 1 ? 'pqdo.' : 'pqdos.');

    $badgeStatusClass = match ($inmueble->badge_status ?? null) {
        'OPORTUNIDAD' => 'pe-property-card__badge--oportunidad',
        'NEGOCIABLE' => 'pe-property-card__badge--negociable',
        'BAJO_DE_PRECIO' => 'pe-property-card__badge--bajo-precio',
        default => null,
    };
@endphp

<article class="pe-property-card">
    <a href="{{ $showUrl }}" class="pe-property-card__media" tabindex="-1" aria-hidden="true">
        @if($fotoUrl)
            <img
                src="{{ $fotoUrl }}"
                alt=""
                class="pe-property-card__image"
                onerror="this.classList.add('is-hidden'); this.nextElementSibling?.classList.remove('is-hidden');"
            >
            <div class="pe-property-card__placeholder pe-property-card__placeholder--fallback is-hidden" aria-hidden="true">
                <img src="{{ asset('images/logo.png') }}" alt="Paseo España Inmobiliaria" class="pe-property-card__placeholder-logo">
            </div>
        @else
            <div class="pe-property-card__placeholder">
                <img src="{{ asset('images/logo.png') }}" alt="Paseo España Inmobiliaria" class="pe-property-card__placeholder-logo">
            </div>
        @endif

        @if($inmueble->disponibilidad_texto)
            <span class="pe-property-card__badge pe-property-card__badge--disponibilidad">
                {{ $inmueble->disponibilidad_texto }}
            </span>
        @endif

        <div class="pe-property-card__badges">
            <div class="pe-property-card__badges-start">
                @if($inmueble->badge_status_etiqueta && $badgeStatusClass)
                    <span class="pe-property-card__badge {{ $badgeStatusClass }}">
                        {{ $inmueble->badge_status_etiqueta }}
                    </span>
                @endif
            </div>
            <div class="pe-property-card__badges-end">
                @if($esNuevo)
                    <span class="pe-property-card__badge pe-property-card__badge--new">Nuevo</span>
                @endif
                <span class="pe-property-card__badge pe-property-card__badge--code">Código: {{ $inmueble->codigo }}</span>
            </div>
        </div>

        <span class="pe-property-card__price">
            {{ $precio }}{{ $ivaPrincipal }}
            @if($precioSecundario)
                <small class="pe-property-card__price-alt">Venta {{ $precioSecundario }}</small>
            @endif
        </span>
    </a>

    <div class="pe-property-card__body">
        <div class="pe-property-card__main">
            <h3 class="pe-property-card__title">
                <a href="{{ $showUrl }}">
                    {{ $inmueble->tipo_inmueble->tipo ?? 'Tipo no asignado' }} — {{ $inmueble->barrio->nombre ?? 'Barrio no asignado' }}
                </a>
            </h3>
            <p class="pe-property-card__location">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                {{ $ciudadNombre }}
            </p>
        </div>

        <ul class="pe-property-card__features">
            <li>
                <i class="fa fa-arrows-alt" aria-hidden="true" title="Área"></i>
                <span>{{ $inmueble->area_construida }} m²</span>
            </li>
            <li>
                <i class="sl sl-icon-drop" aria-hidden="true" title="Baños"></i>
                <span>{{ $inmueble->no_banos }} {{ (int) $inmueble->no_banos === 1 ? 'baño' : 'baños' }}</span>
            </li>
            @if(! empty($inmueble->no_alcobas))
                <li>
                    <i class="fa fa-bed" aria-hidden="true" title="Alcobas"></i>
                    <span>{{ $alcobasTexto }}</span>
                </li>
            @endif
            @if($inmueble->garajes)
                <li>
                    <i class="fa fa-car" aria-hidden="true" title="Parqueaderos"></i>
                    <span>{{ $parqueaderosTexto }}</span>
                </li>
            @endif

        </ul>

        <a href="{{ $showUrl }}" class="pe-property-card__cta">
            Ver propiedad <span aria-hidden="true">→</span>
        </a>

        @if(! empty($showAsesor))
            <div class="pe-property-card__asesor">
                @if($inmueble->asesor)
                    <span><i class="fa fa-user" aria-hidden="true"></i> {{ $inmueble->asesor_nombres }} {{ $inmueble->asesor_apellidos }}</span>
                    <span><i class="fa fa-phone" aria-hidden="true"></i> {{ $inmueble->asesor_telefonos }}</span>
                @else
                    <span><i class="fa fa-user" aria-hidden="true"></i> Sin asesor asignado</span>
                @endif
            </div>
        @endif
    </div>
</article>
