<?php

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
     * Teléfono WhatsApp de una ciudad (columna whatsapp o fallback global).
     */
    function whatsapp_phone_for_ciudad(\App\Models\Ciudad $ciudad): string
    {
        if (! empty($ciudad->whatsapp)) {
            return preg_replace('/\D+/', '', (string) $ciudad->whatsapp);
        }

        return preg_replace('/\D+/', '', (string) config('services.whatsapp.default_phone', ''));
    }
}

if (!function_exists('whatsapp_url_for_area')) {
    /**
     * URL WhatsApp por área (arriendo|venta) y ciudad.
     */
    function whatsapp_url_for_area(string $area, \App\Models\Ciudad $ciudad): ?string
    {
        $template = config("services.whatsapp.messages.{$area}");

        if (! $template) {
            return null;
        }

        $message = str_replace(':ciudad', $ciudad->nombre, $template);
        $phone = whatsapp_phone_for_ciudad($ciudad);

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
