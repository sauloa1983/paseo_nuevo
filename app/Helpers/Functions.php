<?php

if (! function_exists('resolve_public_html_path')) {
    /**
     * Raíz web pública (public_html en hosting, public/ en local).
     */
    function resolve_public_html_path(): string
    {
        static $cached = null;

        if ($cached !== null) {
            return $cached;
        }

        $fromEnv = env('PUBLIC_HTML_PATH');

        if (filled($fromEnv)) {
            $resolved = realpath((string) $fromEnv);

            if ($resolved !== false && is_dir($resolved)) {
                $cached = $resolved;

                return $cached;
            }
        }

        foreach ([
            base_path('../public_html'),
            dirname(base_path()) . DIRECTORY_SEPARATOR . 'public_html',
            base_path('public'),
        ] as $candidate) {
            $resolved = realpath($candidate);

            if ($resolved === false || ! is_dir($resolved)) {
                continue;
            }

            if (is_file($resolved . DIRECTORY_SEPARATOR . 'index.php')) {
                $cached = $resolved;

                return $cached;
            }
        }

        $cached = realpath(base_path('public')) ?: base_path('public');

        return $cached;
    }
}

if (! function_exists('panel_domain')) {
    /**
     * Dominio del panel Filament (panel.paseoespana.com).
     * Lee config y, si hace falta, .env (útil cuando config:cache está desactualizado).
     */
    function panel_domain(): ?string
    {
        static $cached = null;

        if ($cached !== null) {
            return $cached !== '' ? $cached : null;
        }

        $domain = config('app.panel_domain');

        if (filled($domain)) {
            $cached = (string) $domain;

            return $cached;
        }

        $envFile = base_path('.env');

        if (is_readable($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
                $line = trim($line);

                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }

                if (str_starts_with($line, 'FILAMENT_DOMAIN=')) {
                    $value = trim(substr($line, 16));
                    $value = trim($value, " \t\"'");

                    if (filled($value)) {
                        $cached = $value;

                        return $value;
                    }
                }
            }
        }

        $cached = '';

        return null;
    }
}

if (! function_exists('public_storage_root')) {
    function public_storage_root(): string
    {
        return resolve_public_html_path() . DIRECTORY_SEPARATOR . 'storage';
    }
}

if (! function_exists('public_storage_file_path')) {
    function public_storage_file_path(string $diskRelativePath): string
    {
        return public_storage_root() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($diskRelativePath, '/'));
    }
}

if (! function_exists('public_storage_file_exists')) {
    function public_storage_file_exists(string $diskRelativePath): bool
    {
        if (blank($diskRelativePath)) {
            return false;
        }

        return is_file(public_storage_file_path($diskRelativePath));
    }
}

if (! function_exists('public_storage_url')) {
    /**
     * URL pública de un archivo en public_html/storage (fotos/, banners/, etc.).
     * En producción con panel en subdominio, apunta al sitio principal (APP_URL / FOTOS_BASE_URL).
     */
    function public_storage_url(?string $diskRelativePath): ?string
    {
        if (blank($diskRelativePath)) {
            return null;
        }

        $path = ltrim(str_replace('\\', '/', trim($diskRelativePath)), '/');

        if (blank($path)) {
            return null;
        }

        $relativePath = 'storage/' . $path;
        $base = rtrim((string) (config('app.fotos_base_url') ?: config('app.url')), '/');

        $segments = array_map(
            static fn (string $segment): string => rawurlencode(rawurldecode($segment)),
            explode('/', $relativePath),
        );

        return $base . '/' . implode('/', $segments);
    }
}

if (! function_exists('admin_storage_url')) {
    /**
     * URL para previsualización en Filament (mismo origen que el panel).
     * Si el subdominio del panel apunta a public_html (caso típico en cPanel),
     * usa /storage/… relativo. Si no, usa la ruta /media/ del panel.
     */
    function admin_storage_url(?string $diskRelativePath): ?string
    {
        if (blank($diskRelativePath)) {
            return null;
        }

        $path = ltrim(str_replace('\\', '/', trim($diskRelativePath)), '/');

        if (blank($path)) {
            return null;
        }

        $segments = array_map(
            static fn (string $segment): string => rawurlencode(rawurldecode($segment)),
            explode('/', $path),
        );

        $panelDomain = panel_domain();

        if (filled($panelDomain) && request()->getHost() === $panelDomain) {
            // panel.paseoespana.com → public_html (misma carpeta que www)
            return '/storage/' . implode('/', $segments);
        }

        return public_storage_url($path);
    }
}

if (! function_exists('foto_inmueble_url')) {
    /**
     * URL pública de una foto de inmueble (public_html/storage/fotos/…).
     */
    function foto_inmueble_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $filename = ltrim(str_replace('\\', '/', trim($path)), '/');
        $filename = ltrim(str_replace(['storage/fotos/', 'fotos/'], '', $filename), '/');

        if (blank($filename)) {
            return null;
        }

        return public_storage_url('fotos/' . $filename);
    }
}

if (!function_exists('acceso')) {
    function acceso($valor)
    {
        switch ($valor) {
            case 0: return "Vehicular";
            case 1: return "Peatonal";
            default: return "[Sin definir]";
        }
    }
}

if (!function_exists('ubicacion')) {
    function ubicacion($valor)
    {
        switch ($valor) {
            case 0: return "Interior";
            case 1: return "Exterior";
            default: return "[Sin definir]";
        }
    }
}

if (!function_exists('whatsapp_build_url')) {
    function whatsapp_build_url(string $phone, string $message): ?string
    {
        $phone = preg_replace('/\D+/', '', $phone);

        if ($phone === '') {
            return null;
        }

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}

if (!function_exists('whatsapp_phone_for_ciudad')) {
    /**
     * Teléfono WhatsApp de una ciudad por área (arriendo|venta), con fallback legacy y global.
     */
    function whatsapp_phone_for_ciudad(\App\Models\Ciudad $ciudad, string $area = 'arriendo'): string
    {
        $column = $area === 'venta' ? 'whatsapp_venta' : 'whatsapp_arriendo';
        $specific = preg_replace('/\D+/', '', (string) ($ciudad->{$column} ?? ''));

        if ($specific !== '') {
            return $specific;
        }

        $legacy = preg_replace('/\D+/', '', (string) ($ciudad->whatsapp ?? ''));

        if ($legacy !== '') {
            return $legacy;
        }

        return preg_replace('/\D+/', '', (string) config('services.whatsapp.default_phone', ''));
    }
}

if (!function_exists('whatsapp_format_phone')) {
    function whatsapp_format_phone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if ($digits === '') {
            return '';
        }

        if (strlen($digits) === 12 && str_starts_with($digits, '57')) {
            return '+57 '.substr($digits, 2, 3).' '.substr($digits, 5, 3).' '.substr($digits, 8);
        }

        if (strlen($digits) === 10) {
            return '+57 '.substr($digits, 0, 3).' '.substr($digits, 3, 3).' '.substr($digits, 6);
        }

        return '+'.$digits;
    }
}

if (!function_exists('whatsapp_phone_display_for_area')) {
    function whatsapp_phone_display_for_area(string $area, \App\Models\Ciudad $ciudad): string
    {
        return whatsapp_format_phone(whatsapp_phone_for_ciudad($ciudad, $area));
    }
}

if (!function_exists('whatsapp_ciudad_tiene_numero')) {
    /**
     * True si la ciudad tiene número propio (no solo el fallback global).
     */
    function whatsapp_ciudad_tiene_numero(string $area, \App\Models\Ciudad $ciudad): bool
    {
        $column = $area === 'venta' ? 'whatsapp_venta' : 'whatsapp_arriendo';

        if (trim((string) ($ciudad->{$column} ?? '')) !== '') {
            return true;
        }

        return trim((string) ($ciudad->whatsapp ?? '')) !== '';
    }
}

if (!function_exists('whatsapp_url_for_area')) {
    /**
     * URL WhatsApp por área (arriendo|venta) y ciudad.
     */
    function whatsapp_url_for_area(string $area, \App\Models\Ciudad $ciudad): ?string
    {
        $defaults = [
            'arriendo' => 'Hola, me interesa información sobre arriendos en :ciudad.',
            'venta' => 'Hola, me interesa información sobre ventas en :ciudad.',
        ];

        $template = config("services.whatsapp.messages.{$area}")
            ?: ($defaults[$area] ?? null);

        if (! $template) {
            return null;
        }

        $message = str_replace(':ciudad', $ciudad->nombre, $template);
        $phone = whatsapp_phone_for_ciudad($ciudad, $area);

        return whatsapp_build_url($phone, $message);
    }
}

if (!function_exists('whatsapp_url_legal')) {
    function whatsapp_url_legal(): ?string
    {
        $phone = config('services.whatsapp.legal.phone');
        $message = config('services.whatsapp.messages.legal', '');

        return whatsapp_build_url((string) $phone, (string) $message);
    }
}

if (! function_exists('whatsapp_phone_from_asesor_telefonos')) {
    /**
     * Extrae el primer celular colombiano válido (10 dígitos, inicia en 3) del teléfono del asesor.
     */
    function whatsapp_phone_from_asesor_telefonos(?string $telefonos): ?string
    {
        if (blank($telefonos)) {
            return null;
        }

        $parts = preg_split('/[\s,\/;|]+/', trim($telefonos)) ?: [];

        foreach ($parts as $part) {
            $digits = preg_replace('/\D+/', '', (string) $part);

            if ($digits === '') {
                continue;
            }

            if (str_starts_with($digits, '57') && strlen($digits) === 12 && str_starts_with(substr($digits, 2), '3')) {
                return $digits;
            }

            if (strlen($digits) === 10 && str_starts_with($digits, '3')) {
                return '57'.$digits;
            }
        }

        return null;
    }
}

if (! function_exists('whatsapp_url_for_property_asesor')) {
    function whatsapp_url_for_property_asesor(?string $telefonos, string $message): ?string
    {
        $phone = whatsapp_phone_from_asesor_telefonos($telefonos);

        return $phone ? whatsapp_build_url($phone, $message) : null;
    }
}

if (!function_exists('tenant_form_url')) {
    /**
     * URL pública del formulario de estudio (Zurich o El Libertador) según el rol.
     *
     * @param  'libertador'|'zurich'  $provider
     * @param  'natural'|'juridica'  $key
     */
    function tenant_form_url(string $provider, string $key): ?string
    {
        $path = config("tenant_forms.{$provider}.{$key}");

        if (! is_string($path) || $path === '') {
            return null;
        }

        return asset($path);
    }
}

if (! function_exists('tenant_instructivo_url')) {
    /**
     * @param  array{url?: string, file?: string}  $item
     */
    function tenant_instructivo_url(array $item): ?string
    {
        if (! empty($item['url'])) {
            return $item['url'];
        }

        if (! empty($item['file'])) {
            return asset($item['file']);
        }

        return null;
    }
}
