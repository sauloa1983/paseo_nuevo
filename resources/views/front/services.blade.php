@extends('layouts.front')
@section('title', 'Nuestros Servicios - Paseo España Inmobiliaria')

@php
    $whatsappAsesor = whatsapp_url_legal();

    $serviceCards = [
        [
            'id' => 'arriendos',
            'icon' => 'fa-home',
            'title' => 'Arriendos',
            'subtitle' => 'Administración profesional de tu inmueble',
            'features' => [
                'Captación del inmueble',
                'Estudio de arrendatarios',
                'Promoción y mercadeo inmobiliario',
                'Administración y cobro de canon',
                'Respaldo legal y seguros',
            ],
            'cta' => route('inmuebles.search'),
        ],
        [
            'id' => 'ventas',
            'icon' => 'fa-tag',
            'title' => 'Ventas',
            'subtitle' => 'Comercialización estratégica de tu propiedad',
            'features' => [
                'Avalúo comercial',
                'Estrategia de marketing',
                'Publicación en portales y web',
                'Visitas y negociación',
                'Acompañamiento legal hasta escritura',
            ],
            'cta' => route('inmuebles.search'),
        ],
        [
            'id' => 'avaluos',
            'icon' => 'fa-bar-chart',
            'title' => 'Avalúos',
            'subtitle' => 'Valoración precisa según el mercado',
            'features' => [
                'Avalúos comerciales',
                'Avalúos hipotecarios',
                'Análisis de mercado',
                'Asesoría para fijación de precio',
                'Propiedades urbanas y rurales',
            ],
            'cta' => route('contact'),
        ],
    ];

    $processSteps = [
        ['icon' => 'fa-headphones', 'title' => 'Recibimos tu solicitud', 'text' => 'Atendemos tu requerimiento y te orientamos desde el primer contacto.'],
        ['icon' => 'fa-search', 'title' => 'Analizamos tu inmueble', 'text' => 'Evaluamos características, ubicación y potencial del mercado.'],
        ['icon' => 'fa-bullseye', 'title' => 'Definimos la estrategia', 'text' => 'Diseñamos el plan ideal para arriendo, venta o avalúo.'],
        ['icon' => 'fa-bullhorn', 'title' => 'Ejecutamos el servicio', 'text' => 'Implementamos acciones comerciales, legales y administrativas.'],
        ['icon' => 'fa-shield', 'title' => 'Seguimiento permanente', 'text' => 'Te acompañamos con reportes y soporte continuo.'],
    ];

    $faqs = [
        ['id' => 'faq-1', 'q' => '¿Cuál y cuánto es el incremento del canon para la vivienda?', 'a' => 'El incremento corresponde al IPC. Para el año 2026 es del 13,12%.'],
        ['id' => 'faq-2', 'q' => '¿Qué documentos necesito para arrendar un inmueble?', 'a' => 'Solicitamos documentos de identidad, soportes de ingresos, referencias personales y comerciales, y el cumplimiento de los requisitos definidos en la política de la inmobiliaria.'],
        ['id' => 'faq-3', 'q' => '¿Cómo funciona la administración de arriendos?', 'a' => 'Gestionamos la promoción, estudio de arrendatarios, firma de contratos, cobro de canon, pagos al propietario y seguimiento durante toda la vigencia del arriendo.'],
        ['id' => 'faq-4', 'q' => '¿Cuánto tiempo tarda la venta de un inmueble?', 'a' => 'Depende del tipo de inmueble, precio y condiciones del mercado. Con una estrategia adecuada buscamos reducir los tiempos de comercialización.'],
        ['id' => 'faq-5', 'q' => '¿Qué incluye un avalúo comercial?', 'a' => 'Incluye visita al inmueble, análisis comparativo de mercado, metodología técnica y entrega de un informe con el valor comercial de referencia.'],
        ['id' => 'faq-6', 'q' => '¿Ofrecen seguros de arrendamiento?', 'a' => 'Sí. Trabajamos con pólizas que respaldan el canon, administración y amparos integrales según el perfil del inmueble y del arrendatario.'],
    ];
@endphp

@section('content')

<section class="pe-services-hero fullwidth-layout" aria-labelledby="services-hero-heading">
    <div class="pe-services-hero__layout">
        <div class="pe-services-hero__content">
            <div class="pe-services-hero__content-inner">
                <h1 id="services-hero-heading" class="pe-services-hero__title">
                    Servicios inmobiliarios para <span class="pe-services-accent">proteger y potenciar</span> tu patrimonio
                </h1>
                <p class="pe-services-hero__text">
                    Administramos, vendemos y valoramos inmuebles con más de 30 años de experiencia en Santander.
                </p>
                <div class="pe-services-hero__actions">
                    @if ($whatsappAsesor)
                        <a href="{{ $whatsappAsesor }}" target="_blank" rel="noopener noreferrer" class="pe-services-btn pe-services-btn--primary">
                            <i class="fa fa-whatsapp" aria-hidden="true"></i>
                            Hablar con un asesor
                        </a>
                    @endif
                    <a href="{{ route('contact') }}" class="pe-services-btn pe-services-btn--outline">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        Centro de Ayuda
                    </a>
                </div>
            </div>
        </div>

        <div class="pe-services-hero__media">
            <div
                class="pe-services-hero__image"
                style="background-image: url('{{ asset('images/servicios.jpg') }}');"
                role="img"
                aria-label="Vivienda moderna con jardín"
            ></div>
        </div>
    </div>
</section>

<section class="pe-services-offer" aria-labelledby="services-offer-heading">
    <div class="container">
        <p class="titulo-rojo-secciones">Nuestros servicios</p>
        <h2 id="services-offer-heading" class="pe-services-offer__title">Soluciones integrales para cada necesidad</h2>

        <div class="pe-services-offer__grid">
            @foreach ($serviceCards as $card)
                <article class="pe-card" id="servicio-{{ $card['id'] }}">
                    <header class="pe-card__head">
                        <span class="pe-card__icon" aria-hidden="true">
                            <i class="fa {{ $card['icon'] }}"></i>
                        </span>
                        <div>
                            <h3 class="pe-card__title">{{ $card['title'] }}</h3>
                            <p class="pe-card__subtitle">{{ $card['subtitle'] }}</p>
                        </div>
                    </header>
                    <ul class="pe-card__list">
                        @foreach ($card['features'] as $feature)
                            <li>
                                <span class="pe-card__check" aria-hidden="true"><i class="fa fa-check"></i></span>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ $card['cta'] }}" class="pe-card__cta">Conoce más &rarr;</a>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="pe-services-process" aria-labelledby="services-process-heading">
    <div class="container">
        <h2 id="services-process-heading" class="titulo-rojo-secciones">¿Cómo trabajamos?</h2>

        <ol class="pe-services-process__steps">
            @foreach ($processSteps as $index => $step)
                <li class="pe-services-process__step">
                    <div class="pe-services-process__step-inner">
                        <span class="pe-services-process__number">{{ $index + 1 }}</span>
                        <span class="pe-services-process__circle" aria-hidden="true">
                            <i class="fa {{ $step['icon'] }}"></i>
                        </span>
                        <h3 class="sub-texto-iconos_titulo">{{ $index + 1 }}. {{ $step['title'] }}</h3>
                        <p class="sub-texto-iconos-xs">{{ $step['text'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>

<section class="pe-services-faq" aria-labelledby="services-faq-heading" x-data="{ openFaq: null }">
    <div class="container">
        <h2 id="services-faq-heading" class="titulo-negro-secciones">Preguntas frecuentes</h2>
        <span class="pe-services-faq__line" aria-hidden="true"></span>

        <div class="pe-services-faq__grid">
            @foreach ($faqs as $faq)
                <article class="pe-services-faq__item" :class="{ 'is-open': openFaq === '{{ $faq['id'] }}' }">
                    <button
                        type="button"
                        class="pe-services-faq__trigger"
                        @click="openFaq = openFaq === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'"
                        :aria-expanded="openFaq === '{{ $faq['id'] }}'"
                        aria-controls="{{ $faq['id'] }}-panel"
                    >
                        <span>{{ $faq['q'] }}</span>
                        <span class="pe-services-faq__chevron" aria-hidden="true"><i class="fa fa-angle-down"></i></span>
                    </button>
                    <div
                        id="{{ $faq['id'] }}-panel"
                        class="pe-services-faq__panel"
                        x-show="openFaq === '{{ $faq['id'] }}'"
                        x-cloak
                    >
                        <p>{{ $faq['a'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section
    class="banner-rojo"
    style="--banner-rojo-bg: url('{{ asset('images/inmueble_2.jpg') }}');"
    aria-labelledby="about-cta-heading"
>
    <div class="container">
        <div class="banner-rojo__inner">
            <div class="banner-rojo__copy">
                <h2 id="about-cta-heading" class="banner-rojo__title">¿Necesitas asesoría inmobiliaria?</h2>
                <p class="banner-rojo__subtitle">Estamos listos para ayudarte a tomar la mejor decisión.</p>
            </div>
            <div class="banner-rojo__actions">
                <a href="{{ route('inmuebles.search') }}" class="banner-rojo__btn">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    Ver inmuebles disponibles
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
