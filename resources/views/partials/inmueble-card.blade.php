@php
    $showUrl = route('inmuebles.show', $inmueble->codigo);
    $esNuevo = $inmueble->fecha_captacion >= now()->subDays(30);

    $fotoUrl = null;
    $fotos = $inmueble->fotos ?? $inmueble->fotoInmueble ?? null;
    if (! empty($fotos) && $fotos->isNotEmpty()) {
        $fotoUrl = foto_inmueble_url($fotos->first()->foto);
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

    $parqMoto = (string) ($inmueble->parq_moto ?? '') === '1';
    $parqComunal = (string) ($inmueble->parq_comunal ?? '') === '1';

    if ($parqMoto) {
        $parqIcono = 'fa fa-motorcycle';
        $parqueaderosTexto = '1 pqdo.';
        $mostrarParqueadero = true;
    } elseif ($parqComunal) {
        $parqIcono = 'fa fa-car';
        $parqueaderosTexto = 'comunal';
        $mostrarParqueadero = true;
    } elseif (! empty($inmueble->garajes)) {
        $parqIcono = 'fa fa-car';
        $parqueaderosTexto = $inmueble->garajes . ' ' . ((int) $inmueble->garajes === 1 ? 'pqdo.' : 'pqdos.');
        $mostrarParqueadero = true;
    } else {
        $mostrarParqueadero = false;
    }

    $badgeStatusClass = $inmueble->badgeStatusCssClass();
    $esEstrenar = ($inmueble->badge_status ?? null) === 'ESTRENAR';
    $tieneDisponibilidad = filled($inmueble->disponibilidad_texto);
    $tieneEtiquetaComercial = filled($inmueble->badge_status_etiqueta) && filled($badgeStatusClass);
    $etiquetaEnSlotDisponibilidad = $tieneEtiquetaComercial && ! $tieneDisponibilidad;
@endphp

<article @class(['pe-property-card', 'pe-property-card--estrenar' => $esEstrenar])>
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

        @if($tieneDisponibilidad)
            <span class="pe-property-card__badge pe-property-card__badge--disponibilidad">
                {{ $inmueble->disponibilidad_texto }}
            </span>
        @elseif($etiquetaEnSlotDisponibilidad)
            <span class="pe-property-card__badge {{ $badgeStatusClass }} pe-property-card__badge--comercial-slot">
                @if($esEstrenar)
                    <i class="fa fa-star" aria-hidden="true"></i>
                @endif
                {{ $inmueble->badge_status_etiqueta }}
            </span>
        @endif

        <div class="pe-property-card__badges">
            <div class="pe-property-card__badges-end">
                @if($esNuevo)
                    <span class="pe-property-card__badge pe-property-card__badge--new">Nuevo</span>
                @endif
                <span class="pe-property-card__badge pe-property-card__badge--code">Código: {{ $inmueble->codigo }}</span>
            </div>

            @if($tieneEtiquetaComercial && ! $etiquetaEnSlotDisponibilidad)
                <div class="pe-property-card__badges-start">
                    <span class="pe-property-card__badge {{ $badgeStatusClass }}">
                        @if($esEstrenar)
                            <i class="fa fa-star" aria-hidden="true"></i>
                        @endif
                        {{ $inmueble->badge_status_etiqueta }}
                    </span>
                </div>
            @endif
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
            @if($mostrarParqueadero)
                <li>
                    <i class="{{ $parqIcono }}" aria-hidden="true" title="Parqueaderos"></i>
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
