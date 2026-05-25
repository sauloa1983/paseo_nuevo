<?php

namespace App\Http\Controllers;

use App\Models\Inmueble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Property; // De tu Filament Resource
use App\Models\Testimonial;

class FrontController extends Controller
{
    public function index()
    {
        $types = DB::table('tipo_inmueble')
            ->select('id', 'tipo')  // ← ¿Está esto?
            ->orderBy('tipo')
            ->pluck('tipo', 'id');

        //dd($types);

        // Trae inmuebles destacados con foto principal
        $destacados_arriendo = Inmueble::where('destacado', 1)->where('arriendo', 1)->where('estado', 0)
            ->with([
                'fotoInmueble' => function($query) {
                    $query->where('posicion', 0);
                },
                'tipo_inmueble',
                'ciudadRelacion',
                'barrio',

            ])
            ->inRandomOrder() // Para mostrar diferentes destacados cada vez
            ->limit(12) //12
            ->get();

        $destacados_ventas = Inmueble::where('destacado',1)->where('venta',1)->where('estado',0)
            ->with([
                'fotoInmueble' => function($query) {
                    $query->where('posicion', 0);
                },
                'tipo_inmueble',
                'ciudadRelacion',
                'barrio'
            ])
            ->inRandomOrder()
            ->limit(12) //12
            ->get();

        $ciudadesPopulares = Inmueble::select('ciudad', DB::raw('count(*) as total'))
            ->where('estado', 0) // Solo activos
            ->groupBy('ciudad')
            ->orderByDesc('total')
            ->limit(4)
            ->with('ciudadRelacion')  // Carga nombre ciudad
            ->get();


        $ciudades = DB::table('ciudades')
            ->orderBy('nombre')
            ->pluck('nombre', 'id');

        $barrios = collect();  // []

        return view('front.index', compact('types',  'destacados_arriendo', 'destacados_ventas', 'ciudadesPopulares', 'ciudades', 'barrios'));
    }

    public function inmuebles(Request $reques)
    {
        $properties = Inmueble::with('tipo_inmueble')->get();
        return view('front.inmuebles', compact('properties'));
    }

    public function about()
    {
        return view('front.about');
    }

    public function services()
    {
        return view('front.services');
    }
    public function clients()
    {

        $testimonials = Testimonial::where('activo', 1)
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('front.clients', compact('testimonials'));
    }

    public function faq()
    {
        return view('front.faq');
    }

    public function contact()
    {
        return view('front.contact');
    }

    public function requirements()
    {
        return view('front.requirements');
    }

    // Bonus: detalle propiedad
    public function show(Inmueble $inmueble)
    {
        return view('front.show', compact('inmueble'));
    }

}
