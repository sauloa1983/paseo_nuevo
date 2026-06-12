<?php

namespace App\Filament\Resources\Usuarios;

use App\Filament\Resources\Usuarios\Pages\CreateUsuarios;
use App\Filament\Resources\Usuarios\Pages\EditUsuarios;
use App\Filament\Resources\Usuarios\Pages\ListUsuarios;
use App\Filament\Resources\Usuarios\Schemas\UsuariosForm;
use App\Filament\Resources\Usuarios\Tables\UsuariosTable;
use App\Models\Usuario;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UsuariosResource extends Resource
{
    protected static ?string $model = Usuario::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'Usuarios';

    public static function shouldRegisterNavigation(): bool
    {
        return static::usuarioPuedeGestionar();
    }

    public static function canAccess(): bool
    {
        return static::usuarioPuedeGestionar();
    }

    public static function canViewAny(): bool
    {
        return static::usuarioPuedeGestionar();
    }

    public static function canCreate(): bool
    {
        return static::usuarioPuedeGestionar();
    }

    public static function canEdit(Model $record): bool
    {
        return static::usuarioPuedeGestionar();
    }

    public static function canDelete(Model $record): bool
    {
        return static::usuarioPuedeGestionar();
    }

    public static function canDeleteAny(): bool
    {
        return static::usuarioPuedeGestionar();
    }

    protected static function usuarioPuedeGestionar(): bool
    {
        $user = Auth::user();

        return $user instanceof Usuario && $user->puedeGestionarUsuarios();
    }

    public static function form(Schema $schema): Schema
    {
        return UsuariosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsuariosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsuarios::route('/'),
            'create' => CreateUsuarios::route('/create'),
            'edit' => EditUsuarios::route('/{record}/edit'),
        ];
    }
}
