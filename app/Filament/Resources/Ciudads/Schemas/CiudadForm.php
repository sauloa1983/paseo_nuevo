<?php

namespace App\Filament\Resources\Ciudads\Schemas;

use Filament\Actions\IconButtonAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Http\UploadedFile;

class CiudadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1️⃣ Campo NOMBRE
                TextInput::make('nombre')
                    ->label('Nombre de la Ciudad')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'required' => 'El nombre de la ciudad es obligatorio.',
                        'max' => 'El nombre de la ciudad no puede tener más de 100 caracteres.',
                        'unique' => 'Ya existe una ciudad con este nombre. Por favor, elige otro nombre.',
                    ]),

                TextInput::make('whatsapp')
                    ->label('WhatsApp (solo dígitos, ej: 573076978295)')
                    ->tel()
                    ->maxLength(20)
                    ->helperText('Número para el widget de contacto por ciudad.'),

                // 2️⃣ Campo IMAGEN
                FileUpload::make('imagen')
                    ->label('Imagen de la Ciudad')
                    ->image()
                    ->disk('public')
                    ->directory('ciudades')
                    /*->imageEditor()
                    ->imageAspectRatio('16:9')           // v3 OK
                    ->imageResizeTargetWidth(800)        // v3 OK
                    ->imageResizeTargetHeight(600)       // v3 OK
                    ->imageResizeMode('cover')           // v3 OK*/
                    ->maxSize(5120)
                    ->visibility('public')
                    ->validationMessages([
                        'image' => 'El archivo debe ser una imagen válida.',
                        'max' => 'La imagen no puede ser mayor a 5MB.',
                    ]),
            ]);
    }
}
