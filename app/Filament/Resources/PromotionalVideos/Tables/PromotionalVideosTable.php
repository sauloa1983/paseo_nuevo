<?php

namespace App\Filament\Resources\PromotionalVideos\Tables;

use App\Filament\Support\StandardTable;
use App\Filament\Resources\PromotionalVideos\Schemas\PromotionalVideoForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PromotionalVideosTable
{
    public static function configure(Table $table): Table
    {
        return StandardTable::configure($table)
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('video_url')
                    ->label('Enlace')
                    ->limit(50)
                    ->url(fn ($record) => $record->video_url, shouldOpenInNewTab: true),

                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Activo'),
            ])
            ->recordActions([
                StandardTable::editAction('Editar video promocional')
                    ->modalWidth('md')
                    ->modalHeading('Editar video promocional')
                    ->modalSubmitActionLabel('Guardar')
                    ->modalCancelActionLabel('Cancelar')
                    ->mutateFormDataUsing(fn (array $data): array => self::mergeDefaults($data))
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Video actualizado')
                            ->body('El video promocional se actualizó correctamente.'),
                    ),
                StandardTable::deleteAction(
                    'Eliminar video promocional',
                    '¿Eliminar video promocional?',
                    '¿Está seguro de que desea eliminar este video? Esta acción no se puede deshacer.',
                    Notification::make()
                        ->danger()
                        ->title('Video eliminado')
                        ->body('El video promocional se eliminó correctamente.'),
                ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function mergeDefaults(array $data): array
    {
        $data['title'] = PromotionalVideoForm::titleFromUrl($data['video_url'] ?? null);
        $data['is_active'] = $data['is_active'] ?? true;

        return $data;
    }
}
