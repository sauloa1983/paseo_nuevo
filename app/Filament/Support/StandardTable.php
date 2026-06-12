<?php

namespace App\Filament\Support;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Table;

class StandardTable
{
    public const DEFAULT_PER_PAGE = 10;

    /** @var list<int> */
    public const PAGINATION_OPTIONS = [10, 25, 50, 100];

    public static function configure(Table $table): Table
    {
        return $table
            ->extraAttributes(['class' => 'pe-admin-table'])
            ->defaultPaginationPageOption(self::DEFAULT_PER_PAGE)
            ->paginationPageOptions(self::PAGINATION_OPTIONS)
            ->scrollToTopOnPageChange();
    }

    public static function editAction(string $tooltip): EditAction
    {
        return EditAction::make()
            ->icon('heroicon-m-pencil-square')
            ->iconButton()
            ->size('xl')
            ->color('gray')
            ->tooltip($tooltip)
            ->extraAttributes([
                'class' => 'group hover:animate-bounce',
            ]);
    }

    public static function deleteAction(
        string $tooltip,
        string $modalHeading,
        string $modalDescription,
        ?Notification $successNotification = null,
    ): DeleteAction {
        $action = DeleteAction::make()
            ->color('danger')
            ->icon('heroicon-m-trash')
            ->modalIconColor('danger')
            ->modalHeading($modalHeading)
            ->modalDescription($modalDescription)
            ->iconButton()
            ->size('xl')
            ->modalSubmitAction(fn ($action) => $action->color('danger'))
            ->modalCancelAction(fn ($action) => $action->color('gray'))
            ->color('gray')
            ->tooltip($tooltip)
            ->extraAttributes([
                'class' => 'group hover:animate-bounce',
            ]);

        if ($successNotification !== null) {
            $action->successNotification($successNotification);
        }

        return $action;
    }
}
