@extends('layouts.front')
@section('title', 'Requisitos Arrendatarios - Paseo España Inmobiliaria')

@section('content')

@include('partials.banner-hero', [
    'headingId' => 'tenant-hero-heading',
    'title' => 'Requisitos para',
    'accent' => 'arrendatarios',
    'text' => 'Documentación necesaria para arrendar un inmueble con respaldo legal y estudio de crédito.',
    'image' => 'images/arrendatarios.jpg',
    'imageAlt' => 'Sala moderna y luminosa de un apartamento en arriendo',
    'breadcrumbLabel' => 'Arrendatarios',
    'showActions' => true, {{-- Nuevo Prop --}}
])

<section class="pe-requirements" aria-labelledby="tenant-natural-heading">
    <div class="container">
        <p class="titulo-negro-secciones">Persona natural</p>
        <span class="pe-why-choose__line" aria-hidden="true"></span>

        @php
            $tenantNaturalCards = [
                [
                    'icon' => 'fa-user',
                    'title' => 'Arrendatario',
                    'items' => [
                        'Fotocopia de cedula ampliada a 150',
                        'Sí es EMPLEADO. Certificado Laboral y extractos bancarios de su cuenta nomina (últimos tres meses)',
                        'Sí es INDEPENDIENTE. Extractos Bancarios, últimos tres meses y/o soportes demostrables de ingresos',
                    ],
                ],
                [
                    'icon' => 'fa-users',
                    'title' => 'Deudor solidario',
                    'items' => [
                        'Fotocopia de cedula ampliada 150',
                        'Sí es EMPLEADO. Certificado Laboral y extractos bancarios de su cuenta nomina (últimos tres meses)',
                        'Sí es INDEPENDIENTE. Extractos Bancarios, últimos tres meses y/o soportes demostrables de ingresos',
                    ],
                ],
                [
                    'icon' => 'fa-home',
                    'title' => 'Deudor solidario – finca raíz',
                    'items' => [
                        'Fotocopia de cedula ampliada 150',
                        'Sí es EMPLEADO. Certificado Laboral y extractos bancarios de su cuenta nomina (últimos tres meses)',
                        'Sí es INDEPENDIENTE. Extractos Bancarios, últimos tres meses y/o soportes demostrables de ingresos',
                        'Folio de matrícula inmobiliaria de la finca raíz (No mayor a 30 días y libre de limitaciones)',
                    ],
                ],
            ];
        @endphp

        <div class="pe-services-offer__grid">
            @foreach ($tenantNaturalCards as $card)
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

        @include('partials.requirements-form-downloads', [
            'headingId' => 'tenant-natural-downloads-heading',
            'wrapperClass' => 'pe-form-downloads pe-form-downloads--standalone',
            'formKey' => 'natural',
        ])

        <p class="pe-alerta__note">
            <strong>Importante:</strong> ninguno puede estar reportado en DataCrédito, Cifín o cualquier otra entidad.
        </p>
    </div>
</section>

<section class="pe-requirements pe-requirements--alt" aria-labelledby="tenant-juridica-heading">
    <div class="container">
        <p id="tenant-juridica-heading" class="titulo-negro-secciones">Persona jurídica</p>
        <span class="pe-why-choose__line" aria-hidden="true"></span>

        <article class="pe-juridica-card">
            <div class="pe-juridica-card__requirements">
                <header class="pe-card__head">
                    <span class="pe-card__icon" aria-hidden="true">
                        <i class="fa fa-briefcase"></i>
                    </span>
                    <h3 class="pe-card_red__title pe-juridica-card__title">Arrendatario (Persona Jurídica)</h3>
                </header>
                <ul class="pe-card__list">
                    <li>
                        <span class="pe-card__check" aria-hidden="true"><i class="fa fa-check"></i></span>
                        Fotocopia del documento de identidad del representante legal
                    </li>
                    <li>
                        <span class="pe-card__check" aria-hidden="true"><i class="fa fa-check"></i></span>
                        Estados financieros del último año
                    </li>
                    <li>
                        <span class="pe-card__check" aria-hidden="true"><i class="fa fa-check"></i></span>
                        Certificado de cámara y comercio (no mayor a 30 días)
                    </li>
                    <li>
                        <span class="pe-card__check" aria-hidden="true"><i class="fa fa-check"></i></span>
                        Declaración de renta de los últimos dos (2) años
                    </li>
                    <li>
                        <span class="pe-card__check" aria-hidden="true"><i class="fa fa-check"></i></span>
                        Extractos bancarios de los últimos tres meses
                    </li>
                </ul>
            </div>

            @include('partials.requirements-form-downloads', [
                'headingId' => 'tenant-juridica-downloads-heading',
                'wrapperClass' => 'pe-form-downloads pe-juridica-card__downloads',
                'formKey' => 'juridica',
            ])
        </article>
    </div>
</section>

@include('partials.tenant-instructivos')

<section
    class="banner-rojo"
    style="--banner-rojo-bg: url('{{ asset('images/inmueble.jpg') }}');"
    aria-labelledby="tenant-cta-heading"
>
    <div class="container">
        <div class="banner-rojo__inner">
            <div class="banner-rojo__copy">
                <h2 id="tenant-cta-heading" class="banner-rojo__title">¿Buscas inmueble en arriendo?</h2>
                <p class="banner-rojo__subtitle">Explora nuestra oferta disponible y encuentra tu próximo hogar.</p>
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
