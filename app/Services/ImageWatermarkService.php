<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageWatermarkService
{
    private const WATERMARK_PATH = 'images/watermark.png';

    private const WATERMARK_WIDTH_RATIO = 0.20;

    /** Opacidad de la marca de agua (0–100). */
    private const WATERMARK_OPACITY = 80;

    public function applyToUploadedFile(TemporaryUploadedFile $file, string $disk = 'public', string $directory = 'fotos'): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        $this->applyWatermark($manager, $image);

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::uuid()->toString() . '.' . $extension;
        $relativePath = trim($directory . '/' . $filename, '/');

        $encoded = $extension === 'png'
            ? $image->encode(new PngEncoder())
            : $image->encode(new JpegEncoder(quality: 90));

        $storage = Storage::disk($disk);
        $storage->put($relativePath, (string) $encoded);
        $storage->setVisibility($relativePath, 'public');

        return $relativePath;
    }

    /**
     * Aplica marca de agua a un archivo ya guardado en disco (fotos existentes).
     */
    public function applyToStoredFile(string $relativePath, string $disk = 'public'): bool
    {
        $storage = Storage::disk($disk);

        if (! $storage->exists($relativePath)) {
            return false;
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($storage->path($relativePath));

        if (! $this->applyWatermark($manager, $image)) {
            return false;
        }

        $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION) ?: 'jpg');
        $encoded = $extension === 'png'
            ? $image->encode(new PngEncoder())
            : $image->encode(new JpegEncoder(quality: 90));

        $storage->put($relativePath, (string) $encoded);

        return true;
    }

    /**
     * Coloca la marca de agua centrada con opacidad configurada.
     */
    private function applyWatermark(ImageManager $manager, ImageInterface $image): bool
    {
        $watermarkPath = public_path(self::WATERMARK_PATH);

        if (! is_file($watermarkPath)) {
            return false;
        }

        $watermark = $manager->read($watermarkPath);
        $targetWidth = max(80, (int) ($image->width() * self::WATERMARK_WIDTH_RATIO));

        if ($watermark->width() > $targetWidth) {
            $watermark->scale(width: $targetWidth);
        }

        $image->place(
            $watermark,
            'center',
            offset_x: 0,
            offset_y: 0,
            opacity: self::WATERMARK_OPACITY,
        );

        return true;
    }
}
