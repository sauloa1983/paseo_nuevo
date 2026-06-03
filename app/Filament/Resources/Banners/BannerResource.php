<?php

namespace App\Filament\Resources\Banners;

use App\Filament\Resources\Banners\Pages\ManageBanners;
use App\Filament\Resources\Banners\Schemas\BannerForm;
use App\Models\Banner;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Banner publicitario';

    protected static ?string $pluralModelLabel = 'Banners publicitarios';

    protected static ?string $navigationLabel = 'Banners publicitarios';

    protected static ?string $slug = 'banners';

    public static function form(Schema $schema): Schema
    {
        return BannerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Miniatura')
                    ->height(48)
                    ->checkFileExistence(false)
                    ->getStateUsing(fn (Banner $record): ?string => $record->imageUrl()),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->placeholder('—'),

                ToggleColumn::make('is_active')
                    ->label('Estado (Activo/Inactivo)'),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->tooltip('Editar banner')
                    ->modalHeading('Editar banner publicitario')
                    ->modalWidth('lg')
                    ->modalSubmitActionLabel('Guardar cambios')
                    ->modalCancelActionLabel('Cancelar')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Banner actualizado')
                            ->body('El banner se actualizó correctamente.'),
                    ),
                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->iconButton()
                    ->color('danger')
                    ->tooltip('Eliminar banner')
                    ->modalHeading('¿Eliminar banner?')
                    ->modalDescription('Esta acción no se puede deshacer.')
                    ->modalIconColor('danger')
                    ->modalSubmitAction(fn ($action) => $action->color('danger')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBanners::route('/'),
        ];
    }
}
