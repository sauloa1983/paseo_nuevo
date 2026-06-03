<?php

namespace App\Filament\Resources\PromotionalVideos;

use App\Filament\Resources\PromotionalVideos\Pages\ManagePromotionalVideos;
use App\Filament\Resources\PromotionalVideos\Schemas\PromotionalVideoForm;
use App\Filament\Resources\PromotionalVideos\Tables\PromotionalVideosTable;
use App\Models\PromotionalVideo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromotionalVideoResource extends Resource
{
    protected static ?string $model = PromotionalVideo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Video promocional';

    protected static ?string $pluralModelLabel = 'Videos promocionales';

    protected static ?string $navigationLabel = 'Videos promocionales';

    protected static ?string $slug = 'videos-promocionales';

    public static function form(Schema $schema): Schema
    {
        return PromotionalVideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromotionalVideosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePromotionalVideos::route('/'),
        ];
    }
}
