<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageWatermarkService
{
    private const WATERMARK_PATH = 'images/watermark.png';

    private const WATERMARK_WIDTH_RATIO = 0.20;

    private const MAX_WIDTH = 1200;

    private const MAX_HEIGHT = 800;

    private const OUTPUT_QUALITY = 80;

    /** Opacidad de la marca de agua (0–100). */
    private const WATERMARK_OPACITY = 80;

    public function applyToUploadedFile(TemporaryUploadedFile $file, string $disk = 'public', string $directory = 'fotos'): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        $this->optimizeForStorage($image);
        $this->applyWatermark($manager, $image);

        $useWebp = $this->supportsWebp();
        $extension = $useWebp ? 'webp' : 'jpg';
        $filename = Str::uuid()->toString() . '.' . $extension;
        $relativePath = trim($directory . '/' . $filename, '/');

        $encoded = $this->encodeOptimized($image, $useWebp);

        $storage = Storage::disk($disk);
        $storage->put($relativePath, $encoded);
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
     * Reduce dimensiones solo si superan el máximo; nunca escala hacia arriba.
     */
    private function optimizeForStorage(ImageInterface $image): void
    {
        $image->scaleDown(width: self::MAX_WIDTH, height: self::MAX_HEIGHT);
    }

    private function encodeOptimized(ImageInterface $image, bool $useWebp): string
    {
        if ($useWebp) {
            return (string) $image->encode(new WebpEncoder(quality: self::OUTPUT_QUALITY));
        }

        return (string) $image->encode(new JpegEncoder(quality: self::OUTPUT_QUALITY));
    }

    private function supportsWebp(): bool
    {
        return function_exists('imagewebp');
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
