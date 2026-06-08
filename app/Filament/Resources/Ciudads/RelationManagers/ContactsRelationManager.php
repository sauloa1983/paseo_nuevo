<?php

namespace App\Filament\Resources\Ciudads\RelationManagers;

use App\Filament\Support\StandardTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    protected static ?string $title = 'Dependencias y Contactos de la Oficina';

    protected static ?string $recordTitleAttribute = 'department';

    protected function getTableHeading(): ?string
    {
        return null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('department')
                    ->label('Dependencia')
                    ->placeholder('Ej: Comercial (arriendos-ventas)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('phones')
                    ->label('Teléfonos')
                    ->required()
                    ->maxLength(255),

                TextInput::make('manager_name')
                    ->label('Encargado')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return StandardTable::configure($table)
            ->recordTitleAttribute('department')
            ->columns([
                TextColumn::make('department')
                    ->label('Dependencia')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phones')
                    ->label('Teléfonos')
                    ->searchable(),

                TextColumn::make('manager_name')
                    ->label('Encargado')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar dependencia')
                    ->mutateFormDataUsing(function (array $data): array {
                        if (! isset($data['sort_order']) || $data['sort_order'] === '') {
                            $maxOrder = $this->getOwnerRecord()
                                ->contacts()
                                ->max('sort_order');

                            $data['sort_order'] = ($maxOrder ?? -1) + 1;
                        }

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return (bool) $ownerRecord->has_office;
    }
}
