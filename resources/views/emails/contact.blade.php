<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nuevo mensaje de contacto</title>
</head>
<body>
    <h2>Nuevo contacto desde la página</h2>

    <p><strong>Oficina:</strong> {{ $office }}</p>
    <p><strong>Nombre:</strong> {{ $name }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Asunto:</strong> {{ $subject }}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $comments }}</p>
</body>
</html>
