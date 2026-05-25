<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\ManageTestimonials;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Textarea;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleOvalLeft;

    protected static ?string $recordTitleAttribute = 'Testimonios';
    protected static ?string $modelLabel = 'Testimonio';
    protected static ?string $pluralModelLabel = 'Testimonios';
    protected static ?string $navigationLabel = 'Testimonios';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('nombre')
                    ->label('Usuario')
                    ->required()
                    ->maxLength(50)
                    ->validationMessages([
                        'required' => 'Campo obligatorio.',
                        'max' => 'El texto excede los 50 caracteres.',
                    ]),
                TextInput::make('descripcion')
                    ->label('Descripción')
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Debe contener una descripción.',
                        'max' => 'El texto excede los 255 caracteres.',
                    ]),
                Textarea::make('mensaje')
                    ->label('Contenido')
                    ->required()
                    ->rows(6)
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Campo obligatorio.',
                        'max' => 'El texto excede los 255 caracteres.',
                    ]),
                Select::make('activo')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ])
                    ->required()
                    ->validationMessages([
                        'required' => 'El estado es obligatorio.',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Testimonios')
            ->columns([
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->searchable(),
                TextColumn::make('mensaje')
                    ->label('Contenido')
                    ->searchable()
                    ->limit(100),
                TextColumn::make('activo')
                    ->label('Estado')
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Activo' : 'Inactivo')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha creación')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->size('xl')
                    ->color('gray')
                    ->tooltip('Editar')
                    ->modalWidth('md')
                    ->extraAttributes([
                        'class' => 'group hover:animate-bounce',
                    ])
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Testimonio actualizado')
                            ->body('El testimonio se actualizó correctamente.'),
                    ),
                DeleteAction::make()
                    ->color('danger')
                    ->icon('heroicon-m-trash')
                    ->modalIconColor('danger')
                    ->modalHeading('¿Eliminar testimonio?')
                    ->modalDescription('¿Está seguro de que desea eliminar este testimonio? Esta acción no se puede deshacer.')
                    ->iconButton()
                    ->size('xl')
                    ->modalSubmitAction(fn ($action) => $action->color('danger'))
                    ->modalCancelAction(fn ($action) => $action->color('gray'))
                    ->color('gray')
                    ->tooltip('Eliminar testimonio')
                    ->extraAttributes([
                        'class' => 'group hover:animate-bounce',
                    ])
                    ->successNotification(
                        Notification::make()
                            ->danger()
                            ->title('Testimonio eliminado')
                            ->body('El testimonio se eliminó correctamente.'),
                    ),
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
            'index' => ManageTestimonials::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

}
