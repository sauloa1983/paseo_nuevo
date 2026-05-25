<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:100',
            'mensaje' => 'required|string|max:250',
        ]);

        Testimonial::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'mensaje' => $data['mensaje'],
            'activo' => 0,
        ]);

        return back()->with('success', 'Gracias por tu recomendación.');
    }
}
