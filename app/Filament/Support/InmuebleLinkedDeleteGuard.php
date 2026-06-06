<?php

namespace App\Filament\Support;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class InmuebleLinkedDeleteGuard
{
    public static function wrapDeleteAction(DeleteAction $action, string $title): DeleteAction
    {
        return $action->using(function (Model $record) use ($title): bool {
            if (method_exists($record, 'motivoNoEliminable') && ($motivo = $record->motivoNoEliminable())) {
                Notification::make()
                    ->danger()
                    ->title($title)
                    ->body($motivo)
                    ->send();

                return false;
            }

            return (bool) $record->delete();
        });
    }

    public static function wrapDeleteBulkAction(
        DeleteBulkAction $action,
        string $title,
        string $labelAttribute = 'nombre',
    ): DeleteBulkAction {
        return $action->using(function (Collection $records) use ($title, $labelAttribute): void {
            $bloqueados = $records->filter(
                fn (Model $record): bool => method_exists($record, 'cantidadInmueblesAsignados')
                    && $record->cantidadInmueblesAsignados() > 0,
            );

            if ($bloqueados->isNotEmpty()) {
                $nombres = $bloqueados
                    ->map(fn (Model $record): string => (string) $record->{$labelAttribute})
                    ->implode(', ');

                Notification::make()
                    ->danger()
                    ->title($title)
                    ->body("Los siguientes registros tienen inmuebles asociados y no pueden eliminarse: {$nombres}.")
                    ->send();

                return;
            }

            $records->each->delete();
        });
    }
}
