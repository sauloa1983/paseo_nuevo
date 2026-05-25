<x-filament-panels::page>
    {{ $this->form }}
    <x-filament::section>
        <x-slot name="footer">
            <x-filament::button form="data" type="submit">
                Actualizar perfil
            </x-filament::button>
        </x-slot>
    </x-filament::section>
</x-filament-panels::page>
