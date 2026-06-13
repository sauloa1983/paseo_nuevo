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
            'calificacion' => 'required|integer|min:1|max:5',
            'mensaje' => 'required|string|max:250',
        ]);

        Testimonial::create([
            'nombre' => $data['nombre'],
            'calificacion' => $data['calificacion'],
            'mensaje' => $data['mensaje'],
            'activo' => 0,
        ]);

        return back()->with('success', 'Gracias por tu recomendación.');
    }
}
