<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Property; // Cambia por tu modelo

class ListaProperties extends Component
{
    use WithPagination;

    // Si tienes filtros/search, añade aquí como propiedades públicas
    // public $search = '';


    public function render()
    {
        $query = Property::query();
        // $query->when($this->search, fn($q) => $query->where('campo', 'like', "%{$this->search}%"));

        $properties = $query->paginate(12); // 12 ítems por página
        return view('livewire.lista-properties', compact('properties'));
    }
}
