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

    /**
     * @return list<string>
     */
    private function watermarkCandidates(): array
    {
        return array_values(array_unique(array_filter([
            public_path(self::WATERMARK_PATH),
            resolve_public_html_path() . DIRECTORY_SEPARATOR . self::WATERMARK_PATH,
            base_path('public' . DIRECTORY_SEPARATOR . self::WATERMARK_PATH),
        ])));
    }

    private function resolveWatermarkPath(): ?string
    {
        foreach ($this->watermarkCandidates() as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private const WATERMARK_WIDTH_RATIO = 0.50;

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

        $this->writePublicStorageFile($relativePath, $encoded);

        return $relativePath;
    }

    /**
     * Aplica marca de agua a un archivo ya guardado en disco (fotos existentes).
     */
    public function applyToStoredFile(string $relativePath, string $disk = 'public'): bool
    {
        if (! public_storage_file_exists($relativePath) && ! Storage::disk($disk)->exists($relativePath)) {
            return false;
        }

        $absolutePath = public_storage_file_exists($relativePath)
            ? public_storage_file_path($relativePath)
            : Storage::disk($disk)->path($relativePath);

        $manager = new ImageManager(new Driver());
        $image = $manager->read($absolutePath);

        if (! $this->applyWatermark($manager, $image)) {
            return false;
        }

        $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION) ?: 'jpg');
        $encoded = $extension === 'png'
            ? $image->encode(new PngEncoder())
            : $image->encode(new JpegEncoder(quality: 90));

        $this->writePublicStorageFile($relativePath, (string) $encoded);

        return true;
    }

    /**
     * Escribe en public_html/storage/… (no en storage/app/public del proyecto).
     *
     * @throws \RuntimeException
     */
    private function writePublicStorageFile(string $relativePath, string $contents): void
    {
        $absolutePath = public_storage_file_path($relativePath);
        $directory = dirname($absolutePath);

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new \RuntimeException('No se pudo crear la carpeta: ' . $directory);
        }

        if (! is_writable($directory)) {
            throw new \RuntimeException('La carpeta no tiene permisos de escritura: ' . $directory);
        }

        if (file_put_contents($absolutePath, $contents) === false) {
            throw new \RuntimeException('No se pudo guardar la imagen en: ' . $absolutePath);
        }

        @chmod($absolutePath, 0644);
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
        $watermarkPath = $this->resolveWatermarkPath();

        if ($watermarkPath === null) {
            report(new \RuntimeException(
                'Marca de agua no encontrada. Sube public/images/watermark.png a public_html/images/ en el servidor. Rutas probadas: '
                . implode(', ', $this->watermarkCandidates()),
            ));

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
