<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Paseo España Inmobiliaria')</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="padding:24px 28px 16px;text-align:center;border-bottom:1px solid #f3f4f6;">
                            @php
                                $logoPath = public_html_file('images/logo_v.png')
                                    ?? public_html_file('images/logo.png');
                            @endphp
                            @if ($logoPath)
                                <img
                                    src="{{ $message->embed($logoPath) }}"
                                    alt="Paseo España Inmobiliaria"
                                    width="72"
                                    height="72"
                                    style="display:block;margin:0 auto 12px;border-radius:50%;"
                                >
                            @endif
                            <div style="font-size:18px;font-weight:700;color:#c81517;">Paseo España Inmobiliaria</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 28px 28px;font-size:15px;line-height:1.6;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px 24px;font-size:12px;line-height:1.5;color:#6b7280;text-align:center;border-top:1px solid #f3f4f6;">
                            Este correo fue enviado desde el formulario de contacto de
                            <a href="https://paseoespana.com" style="color:#c81517;text-decoration:none;">paseoespana.com</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
