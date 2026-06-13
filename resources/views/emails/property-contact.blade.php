@extends('emails.layout')

@section('title', 'Interés en el inmueble')

@section('content')
    <h2 style="margin:0 0 16px;font-size:20px;color:#111827;">Nuevo contacto desde la página del inmueble</h2>

    <p style="margin:0 0 10px;"><strong>Código inmueble:</strong> {{ $property->codigo }}</p>
    <p style="margin:0 0 10px;"><strong>Email cliente:</strong> {{ $contactData['email'] ?: 'No indicado' }}</p>
    <p style="margin:0 0 10px;"><strong>Teléfono:</strong> {{ $contactData['phone'] }}</p>
    <p style="margin:0 0 8px;"><strong>Mensaje:</strong></p>
    <p style="margin:0;padding:14px 16px;background:#f9fafb;border-radius:8px;border:1px solid #e5e7eb;">{{ $contactData['message'] }}</p>
@endsection
