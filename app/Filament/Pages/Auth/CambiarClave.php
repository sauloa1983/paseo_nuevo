<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Usuarios\Schemas\UsuariosForm;
use App\Models\Usuario;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasMaxWidth;
use Filament\Pages\Concerns\HasTopbar;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Equivalente a cambiar-clave.php: pantalla obligatoria para usuarios
 * que ingresaron con la contraseña por defecto del sistema.
 */
class CambiarClave extends Page
{
    use HasMaxWidth;
    use HasTopbar;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'cambiar-clave';

    protected static ?string $title = 'Cambiar contraseña';

    protected static string $layout = 'filament-panels::components.layout.simple';

    protected string $view = 'filament-panels::pages.simple';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        if (! session('clave_por_defecto', false)) {
            $this->redirect(Dashboard::getUrl());

            return;
        }

        $this->form->fill();
    }

    protected function getLayoutData(): array
    {
        return [
            'hasTopbar' => $this->hasTopbar(),
            'maxContentWidth' => $maxContentWidth = $this->getMaxWidth() ?? $this->getMaxContentWidth(),
            'maxWidth' => $maxContentWidth,
        ];
    }

    public function hasLogo(): bool
    {
        return true;
    }

    public function getHeading(): string | Htmlable
    {
        return 'Debes cambiar tu contraseña';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Por seguridad, no puedes usar la clave por defecto. Define una contraseña nueva para continuar en el panel.';
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('password')
                    ->label('Nueva contraseña')
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required()
                    ->minLength(8)
                    ->same('password_confirmation')
                    ->validationAttribute('nueva contraseña')
                    ->rule(function (): \Closure {
                        return function (string $attribute, mixed $value, \Closure $fail): void {
                            if ($value === UsuariosForm::DEFAULT_PASSWORD) {
                                $fail('La nueva contraseña no puede ser "' . UsuariosForm::DEFAULT_PASSWORD . '".');
                            }
                        };
                    }),
                TextInput::make('password_confirmation')
                    ->label('Confirmar nueva contraseña')
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required()
                    ->validationAttribute('confirmación de contraseña'),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('cambiarClave')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->fullWidth($this->hasFullWidthFormActions())
                    ->key('form-actions'),
            ]);
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getCambiarClaveFormAction(),
        ];
    }

    protected function getCambiarClaveFormAction(): Action
    {
        return Action::make('cambiarClave')
            ->label('Guardar nueva contraseña')
            ->submit('cambiarClave');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    public function cambiarClave(): void
    {
        $data = $this->form->getState();

        if ($data['password'] !== $data['password_confirmation']) {
            throw ValidationException::withMessages([
                'data.password_confirmation' => 'Las contraseñas no coinciden.',
            ]);
        }

        if ($data['password'] === UsuariosForm::DEFAULT_PASSWORD) {
            throw ValidationException::withMessages([
                'data.password' => 'La nueva contraseña no puede ser la clave por defecto del sistema.',
            ]);
        }

        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $usuario->password = $data['password'];
        $usuario->save();

        session()->forget('clave_por_defecto');

        Notification::make()
            ->success()
            ->title('Contraseña actualizada')
            ->body('Tu contraseña se cambió correctamente. Ya puedes usar el panel con normalidad.')
            ->send();

        $this->redirect(Dashboard::getUrl(), navigate: true);
    }
}
