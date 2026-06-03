<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasVideoPrincipal
{
    /**
     * Video propio del inmueble (campo video).
     */
    protected function videoPrincipal(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => filled($this->video) ? trim((string) $this->video) : null,
        );
    }

    /**
     * URL lista para iframe (YouTube nocookie / Vimeo player).
     */
    protected function videoPrincipalEmbed(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => self::videoUrlToEmbed($this->video_principal),
        );
    }

    public static function videoUrlToEmbed(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $url = trim($url);

        if (preg_match('/(?:youtube\.com\/embed\/|youtube-nocookie\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1] . '?rel=0&showinfo=0';
        }

        if (preg_match('/(?:youtube\.com\/watch\?.*v=|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1] . '?rel=0&showinfo=0';
        }

        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        if (preg_match('/player\.vimeo\.com\/video\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }
}
