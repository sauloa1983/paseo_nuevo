@extends('layouts.front')
@section('title', 'Requisitos Propietarios - Paseo España Inmobiliaria')

@section('content')

@include('partials.banner-hero', [
    'headingId' => 'property-hero-heading',
    'title' => 'Requisitos para',
    'accent' => 'propietarios',
    'text' => 'Documentación para entregar su inmueble en administración, arriendo o venta con respaldo profesional.',
    'image' => 'images/inmueble_2.jpg',
    'breadcrumbLabel' => 'Propietarios',
    'showActions' => true, {{-- Nuevo Prop --}}
])

<section class="pe-requirements" aria-labelledby="property-docs-heading">
    <div class="container">
        <p id="property-docs-heading" class="titulo-negro-secciones">Documentación</p>
        <span class="pe-why-choose__line" aria-hidden="true"></span>

        @php
            $propertyDocsCards = [
                [
                    'icon' => 'fa-file-o',
                    'title' => 'Anexos para consignar en Arriendo',
                    'items' => [
                        'Entrega De Llaves Del Inmueble',
                        'Copia De La Cedula De Ciudadanía Del Propietario',
                        'Copia Del Rut, Cuando Sea Inmueble Comercial (Para Verificación De Régimen Del Iva)',
                        'Copia De Primeras (5) Hojas De La Escritura O Hasta Donde Esten Linderos Del Inmueble',
                        'Copia Del Certificado De Tradición Y Libertad Reciente (No Mayor A 30 Días)',
                        'Copia De Ultimo Recibo De Impuesto Predial',
                        'Copia Últimos Recibos De Servicios Públicos',
                        'Copia de Recibo Pago de Admon Y Paz Y Salvo De Administración Propiedad Horizontal (cuando aplica)',
                        'Copia Digital Del Reglamento De Propiedad Horizontal (Sí aplica)',
                        'Ultimo Certificado De Mantenimiento Aire Acondicionado y Calentador (Cuando aplica)',
                    ],
                ],
                [
                    'icon' => 'fa-list-alt',
                    'title' => 'Anexos para consignar en Venta',
                    'items' => [
                        'Copia Del Certificado De Tradición Y Libertad Reciente (No Mayor A 30 Días)',
                        'Copia De La Cedula De Ciudadanía Del Propietario',
                        'Copia de escritura hasta linderos incluidos los de propiedad horizontal (sí este aplica).',
                        'Copia del recibo del impuesto predial actual.',
                        'Entrega de Llaves del Inmueble (sí está desocupado)',
                        'Cancelación preventa de doscientos mil pesos, que serán reembolsados una vez se realice el cierre de venta',
                    ],
                ],
            ];
        @endphp

        <div class="pe-services-offer__grid pe-services-offer__grid--two">
            @foreach ($propertyDocsCards as $card)
                <article class="pe-card">
                    <header class="pe-card__head">
                        <span class="pe-card__icon" aria-hidden="true">
                            <i class="fa {{ $card['icon'] }}"></i>
                        </span>
                        <h3 class="pe-card_red__title">{{ $card['title'] }}</h3>
                    </header>
                    <ul class="pe-card__list">
                        @foreach ($card['items'] as $item)
                            <li>
                                <span class="pe-card__check" aria-hidden="true"><i class="fa fa-check"></i></span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="pe-requirements pe-requirements--alt" aria-labelledby="property-services-heading">
    <div class="container">
        <p id="property-services-heading" class="titulo-negro-secciones">Nuestro servicio</p>
        <span class="pe-why-choose__line" aria-hidden="true"></span>

        @php
            $propertyServiceCards = [
                [
                    'icon' => 'fa-key',
                    'title' => 'Administración de arriendos',
                    'items' => [
                        'Captación y promoción del inmueble',
                        'Estudio de arrendatarios',
                        'Firma de contratos y cobro de canon',
                        'Pagos al propietario y seguimiento permanente',
                        'Respaldo legal y seguros de arrendamiento',
                    ],
                ],
                [
                    'icon' => 'fa-tag',
                    'title' => 'Venta de inmuebles',
                    'items' => [
                        'Avalúo comercial y estrategia de precio',
                        'Publicación en portales y página web',
                        'Visitas, negociación y acompañamiento hasta escritura',
                    ],
                ],
            ];
        @endphp

        <div class="pe-services-offer__grid pe-services-offer__grid--two">
            @foreach ($propertyServiceCards as $card)
                <article class="pe-card">
                    <header class="pe-card__head">
                        <span class="pe-card__icon" aria-hidden="true">
                            <i class="fa {{ $card['icon'] }}"></i>
                        </span>
                        <h3 class="pe-card_red__title">{{ $card['title'] }}</h3>
                    </header>
                    <ul class="pe-card__list">
                        @foreach ($card['items'] as $item)
                            <li>
                                <span class="pe-card__check" aria-hidden="true"><i class="fa fa-check"></i></span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>

        <p class="pe-alerta__note">
            <strong>Nota:</strong> un asesor le orientará sobre la documentación según el tipo de inmueble y el servicio que desee contratar.
        </p>
    </div>
</section>

@include('partials.property-instructivos')

<section
    class="banner-rojo"
    style="--banner-rojo-bg: url('{{ asset('images/home-parallax.jpg') }}');"
    aria-labelledby="property-cta-heading"
>
    <div class="container">
        <div class="banner-rojo__inner">
            <div class="banner-rojo__copy">
                <h2 id="property-cta-heading" class="banner-rojo__title">¿Desea entregar su inmueble?</h2>
                <p class="banner-rojo__subtitle">Contáctenos y un asesor le explicará el proceso paso a paso.</p>
            </div>
            <div class="banner-rojo__actions">
                <a href="{{ route('contact') }}" class="banner-rojo__btn">
                    <i class="fa fa-phone" aria-hidden="true"></i>
                    Centro de Ayuda
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
