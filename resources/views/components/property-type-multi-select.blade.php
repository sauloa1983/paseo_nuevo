@props([
    'options' => [],
    'name' => 'type',
    'placeholder' => 'Tipo Inmueble',
    'selected' => [],
])

@php
    $selectedIds = collect((array) $selected)
        ->filter(fn ($id) => $id !== '' && $id !== null)
        ->map(fn ($id) => (string) $id)
        ->values()
        ->all();

    $optionsList = collect($options)
        ->map(fn ($label, $id) => [
            'id' => (string) $id,
            'label' => ucfirst((string) $label),
        ])
        ->values()
        ->all();
@endphp

<div
    {{ $attributes->merge(['class' => 'property-type-multiselect']) }}
    x-data="{
        open: false,
        placeholder: @js($placeholder),
        options: @js($optionsList),
        selected: @js($selectedIds),
        get label() {
            if (this.selected.length === 0) {
                return this.placeholder;
            }
            if (this.selected.length === 1) {
                const match = this.options.find(o => o.id === this.selected[0]);
                return match ? match.label : this.placeholder;
            }
            return this.selected.length + ' tipos seleccionados';
        },
        isSelected(id) {
            return this.selected.includes(id);
        },
        toggle(id) {
            if (this.isSelected(id)) {
                this.selected = this.selected.filter(item => item !== id);
            } else {
                this.selected = [...this.selected, id];
            }
        },
        clear() {
            this.selected = [];
        }
    }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
>
    <button
        type="button"
        class="property-type-multiselect__trigger"
        :class="{ 'is-placeholder': selected.length === 0 }"
        @click="open = !open"
        :aria-expanded="open"
        aria-haspopup="listbox"
    >
        <span class="property-type-multiselect__label" x-text="label"></span>
    </button>

    <div
        class="property-type-multiselect__dropdown"
        x-show="open"
        x-transition.opacity.duration.150ms
        x-cloak
        role="listbox"
        :aria-hidden="!open"
    >
        <div class="property-type-multiselect__actions" x-show="selected.length > 0">
            <button type="button" class="property-type-multiselect__clear" @click="clear()">
                Limpiar selección
            </button>
        </div>

        <template x-for="option in options" :key="option.id">
            <label class="property-type-multiselect__option" role="option">
                <input
                    type="checkbox"
                    class="property-type-multiselect__checkbox"
                    :value="option.id"
                    :checked="isSelected(option.id)"
                    @change="toggle(option.id)"
                >
                <span class="property-type-multiselect__check" aria-hidden="true"></span>
                <span class="property-type-multiselect__option-text" x-text="option.label"></span>
            </label>
        </template>
    </div>

    <template x-for="id in selected" :key="id">
        <input type="hidden" name="{{ $name }}[]" :value="id">
    </template>
</div>
