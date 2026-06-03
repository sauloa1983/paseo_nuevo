@php
$record = $getRecord();
$state = $record?->foto ?? null;  // ✅ Usa $getRecord()->foto
$url = $state ? asset('storage/' . ltrim($state, '/')) : null;
@endphp

{{-- Preview --}}
@if($url)
    <div class="space-y-2 text-center">
        <img src="{{ $url }}"
             alt="Foto"
             class="w-36 h-36 object-cover rounded-lg shadow-md border mx-auto block"
             loading="lazy"
             onerror="this.src='/images/no-image.jpg'">

        <div>
            <p class="text-sm font-medium truncate">{{ basename($state) }}</p>
            <p class="text-xs text-gray-500">ID: {{ $record->id }}</p>
        </div>
    </div>
@else
    <div class="text-gray-400 text-sm py-8 text-center">
        Sin foto
    </div>
@endif
