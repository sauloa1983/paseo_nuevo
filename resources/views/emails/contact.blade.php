@extends('emails.layout')

@section('title', 'Nuevo mensaje de contacto')

@section('content')
    <h2 style="margin:0 0 16px;font-size:20px;color:#111827;">Nuevo contacto desde la página</h2>

    <p style="margin:0 0 10px;"><strong>Oficina:</strong> {{ $office }}</p>
    <p style="margin:0 0 10px;"><strong>Nombre:</strong> {{ $name }}</p>
    <p style="margin:0 0 10px;"><strong>Email:</strong> {{ $email }}</p>
    <p style="margin:0 0 10px;"><strong>Asunto:</strong> {{ $subject }}</p>
    <p style="margin:0 0 8px;"><strong>Mensaje:</strong></p>
    <p style="margin:0;padding:14px 16px;background:#f9fafb;border-radius:8px;border:1px solid #e5e7eb;">{{ $comments }}</p>
@endsection
