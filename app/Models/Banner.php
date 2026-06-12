<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'link_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        $deactivateOthers = function (Banner $banner): void {
            if (! $banner->is_active) {
                return;
            }

            static::query()
                ->when($banner->exists, fn ($query) => $query->whereKeyNot($banner->getKey()))
                ->update(['is_active' => false]);
        };

        static::creating($deactivateOthers);

        static::updating(function (Banner $banner) use ($deactivateOthers): void {
            if ($banner->is_active) {
                $deactivateOthers($banner);
            }
        });
    }

    public function imageUrl(): ?string
    {
        return self::storagePublicUrl($this->image_path);
    }

    /**
     * URL pública de un archivo en storage/app/public/banners/.
     */
    public static function storagePublicUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $normalized = str_replace('\\', '/', trim($path));
        $normalized = ltrim(str_replace(['storage/', 'public/'], '', $normalized), '/');
        $normalized = ltrim(str_replace('banners/', '', $normalized), '/');

        if (blank($normalized)) {
            return null;
        }

        return public_storage_url('banners/' . $normalized);
    }

    /**
     * Ruta relativa al disco public (p. ej. banners/archivo.jpg).
     */
    public static function normalizeDiskPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $normalized = str_replace('\\', '/', trim($path));
        $normalized = ltrim(str_replace(['storage/', 'public/'], '', $normalized), '/');
        $normalized = ltrim(str_replace('banners/', '', $normalized), '/');

        return filled($normalized) ? 'banners/' . $normalized : null;
    }
}
