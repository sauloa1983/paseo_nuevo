@extends('layouts.front')
@section('title', 'Nuestros Clientes - Paseo España Inmobiliaria')

@section('content')

@include('partials.banner-hero', [
    'headingId' => 'clients-hero-heading',
    'title' => 'Nuestros Clientes',
    'accent' => '',
    'text' => 'Más de 30 años construyendo relaciones de confianza y ayudando a miles de familias a encontrar su lugar ideal.',
    'image' => 'images/clientes.jpg',
    'imageAlt' => 'Apretón de manos simbolizando confianza entre clientes y la inmobiliaria',
    'breadcrumbLabel' => '',
    'showActions' => false, {{-- Nuevo Prop --}}
])

@include('partials.client-opinion', ['testimonials' => $testimonials])

@endsection
