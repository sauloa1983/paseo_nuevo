<?php

namespace App\Filament\Resources\Banners\Schemas;

use App\Models\Banner;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BannerForm
{
    /**
     * Ruta relativa al disco `public` (p. ej. banners/archivo.jpg).
     */
    public static function normalizeImagePath(mixed $state): ?string
    {
        if (is_array($state)) {
            $state = Arr::first($state);
        }

        if (! is_string($state)) {
            return null;
        }

        return Banner::normalizeDiskPath($state);
    }

    public static function imagePublicUrl(string $diskPath): string
    {
        return admin_storage_url($diskPath) ?? '';
    }

    private static function imageMimeType(string $diskPath): string
    {
        return match (strtolower(pathinfo($diskPath, PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            default => 'image/jpeg',
        };
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('title')
                    ->label('Título')
                    ->maxLength(255),

                FileUpload::make('image_path')
                    ->label('Imagen del Banner')
                    ->image()
                    ->disk('public')
                    ->directory('banners')
                    ->visibility('public')
                    ->imagePreviewHeight('180px')
                    ->panelLayout('compact')
                    ->openable()
                    ->previewable()
                    ->downloadable()
                    ->fetchFileInformation(false)
                    ->getUploadedFileUsing(function (
                        FileUpload $component,
                        string $file,
                        string | array | null $storedFileNames,
                    ): ?array {
                        $path = self::normalizeImagePath($file) ?? $file;
                        $storage = $component->getDisk();

                        if (! $storage->exists($path)) {
                            return null;
                        }

                        return [
                            'name' => (is_array($storedFileNames)
                                ? ($storedFileNames[$file] ?? null)
                                : $storedFileNames) ?? basename($path),
                            'size' => $storage->size($path),
                            'type' => self::imageMimeType($path),
                            'url' => self::imagePublicUrl($path),
                        ];
                    })
                    ->getOpenableFileUrlUsing(
                        fn (string $file): string => self::imagePublicUrl(
                            self::normalizeImagePath($file) ?? $file,
                        ),
                    )
                    ->afterStateHydrated(function (FileUpload $component, string | array | null $state): void {
                        if (blank($state)) {
                            return;
                        }

                        $disk = $component->getDisk();
                        $path = self::normalizeImagePath($state);

                        if (blank($path) || ! $disk->exists($path)) {
                            $component->rawState([]);

                            return;
                        }

                        $component->rawState([(string) Str::uuid() => $path]);
                    })
                    ->dehydrateStateUsing(fn (mixed $state): ?string => self::normalizeImagePath($state))
                    ->required()
                    ->maxSize(5120)
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ])
                    ->validationMessages([
                        'required' => 'Debe subir una imagen para el banner.',
                        'image' => 'El archivo debe ser una imagen válida.',
                        'max' => 'La imagen no puede ser mayor a 5 MB.',
                    ]),

                TextInput::make('link_url')
                    ->label('Enlace de redirección (URL)')
                    ->url()
                    ->maxLength(500),

                Toggle::make('is_active')
                    ->label('¿Banner Activo?')
                    ->live()
                    ->default(false),
            ]);
    }
}
