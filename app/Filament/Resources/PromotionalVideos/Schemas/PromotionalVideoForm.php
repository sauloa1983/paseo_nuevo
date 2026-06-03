<?php

namespace App\Filament\Resources\PromotionalVideos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromotionalVideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components(self::modalComponents());
    }

    /**
     * Formulario del modal (crear / editar): solo el enlace del video.
     *
     * @return array<int, TextInput>
     */
    public static function modalComponents(): array
    {
        return [
            TextInput::make('video_url')
                ->label('Enlace del video')
                ->url()
                ->required()
                ->maxLength(500)
                ->placeholder('Ej: https://www.youtube.com/watch?v=...')
                ->helperText('Puedes pegar un enlace de YouTube o Vimeo.'),
            Toggle::make('is_active')
                ->label('Activo')
                ->default(true)
                ->onColor('success')
                ->offColor('danger'),
        ];
    }

    public static function titleFromUrl(?string $url): string
    {
        if (blank($url)) {
            return 'Video promocional';
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        if (str_contains($host, 'youtube.com') || str_contains($host, 'youtu.be')) {
            return 'Video YouTube';
        }

        if (str_contains($host, 'vimeo.com')) {
            return 'Video Vimeo';
        }

        return 'Video promocional';
    }
}
