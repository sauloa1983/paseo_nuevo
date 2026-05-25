<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Interés en el inmueble</title>
</head>
<body>
    <h2>Nuevo contacto desde la página del inmueble</h2>

    <p><strong>Código inmueble:</strong> {{ $property->codigo }}</p>
    <p><strong>Email cliente:</strong> {{ $contactData['email'] }}</p>
    <p><strong>Teléfono:</strong> {{ $contactData['phone'] }}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $contactData['message'] }}</p>
</body>
</html>
