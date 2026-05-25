<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Laravel\Facades\Image; // ✅ vía intervention/image-laravel
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\AlignPosition;

class FotoInmueble extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'fotos_inmuebles';

    public $timestamps = true;

    protected $fillable = ['foto', 'inmueble_fk', 'posicion'];

    protected $casts = ['foto' => 'string'];


    public function inmueble()
    {
        return $this->belongsTo(Inmueble::class, 'inmueble_fk');
    }

    // URL pública de la foto
    public function getUrlAttribute()
    {
        return $this->foto
            /*? asset('storage/fotos/' . $this->file)*/
            ? asset('storage/fotos/' . $this->foto)
            : null;
    }

    // En tu modelo (ej: FotoInmueble.php)
    protected function foto(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? "fotos/{$value}" : null,
            set: fn ($value) => str_replace('fotos/', '', $value),
        );
        /*return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => str_replace('storage/', '', $value),
        );*/
    }

    public function registerMediaCollections(): void {
        $this->addMediaCollection('fotos');
    }

    /*
    public function registerMediaConversions(?Media $media = null): void {
        $this->addMediaConversion('watermarked')
         ->performOnCollections('fotos')
         ->watermark(public_path('images/watermark.png'), position: AlignPosition::BottomRight)
         ->watermarkPadding(20, 20);
    }*/

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('watermarked')
            ->watermark(public_path('images/watermark.png'), position: AlignPosition::BottomRight)
            // Otras posiciones:
            // AlignPosition::TopLeft
            // AlignPosition::BottomLeft
            // AlignPosition::TopRight
            // AlignPosition::Center
            ->watermarkPadding(20, 20);  // Distancia del borde
    }

    /*public function getFotoPreviewAttribute()
    {
        return asset('storage/fotos/' . $this->foto);
    }*/
}
