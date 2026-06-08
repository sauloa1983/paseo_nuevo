<?php

namespace App\Filament\Support;

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
}
