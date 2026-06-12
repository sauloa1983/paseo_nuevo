<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Inmueble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Property; // De tu Filament Resource
use App\Models\Ciudad;
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

        // Destacados limitados a 5 cupos por sede (ciudad), con rotación aleatoria
        $destacados_arriendo = $this->destacadosPorSede('arriendo');
        $destacados_ventas   = $this->destacadosPorSede('venta');

        $ciudadesPopulares = Inmueble::select('ciudad', DB::raw('count(*) as total'))
            ->where('estado', 0)
            ->whereHas('ciudadRelacion', fn ($query) => $query->visibleInBuscador())
            ->groupBy('ciudad')
            ->orderByDesc('total')
            ->limit(4)
            ->with(['ciudadRelacion' => fn ($query) => $query->visibleInBuscador()])
            ->get();

        $ciudades = Ciudad::query()
            ->visibleInBuscador()
            ->orderBy('nombre')
            ->pluck('nombre', 'id');

        $barrios = collect();  // []

        $activeBanner = Banner::where('is_active', true)->first();

        return view('front.index', compact(
            'types',
            'destacados_arriendo',
            'destacados_ventas',
            'ciudadesPopulares',
            'ciudades',
            'barrios',
            'activeBanner',
        ));
    }

    /**
     * Obtiene los inmuebles destacados limitando a un máximo de cupos por sede (ciudad).
     *
     * Usa ROW_NUMBER() OVER (PARTITION BY ciudad ...) para numerar los inmuebles
     * dentro de cada ciudad y conservar solo los primeros N. El orden aleatorio se
     * calcula en una subconsulta interna (columna `rnd`) para no usar RAND() dentro
     * de la cláusula ORDER BY de la ventana y garantizar rotación en cada carga.
     * Si una sede tiene menos de N destacados, simplemente devuelve los que tenga.
     *
     * @param  string  $tipo     'arriendo' | 'venta'
     * @param  int     $porSede  Máximo de inmuebles por ciudad/sede
     */
    private function destacadosPorSede(string $tipo, int $porSede = 5)
    {
        // Columna de precio relevante según el tipo de operación
        $precioCol = $tipo === 'arriendo' ? 'valor_arriendo' : 'valor_venta';

        // 1) Base: destacados activos del tipo solicitado + valor aleatorio por fila.
        //    Se excluyen registros sin tipo (título "N/A") o sin precio válido (> 0).
        $base = Inmueble::query()
            ->select('id', 'ciudad')
            ->selectRaw('RAND() as rnd')
            ->where('destacado', 1)
            ->where('estado', 0)
            ->where($tipo, 1)
            ->whereNotNull('tipo_fk')
            ->where('tipo_fk', '!=', 0)
            ->where($precioCol, '>', 0);

        // 2) Numera cada inmueble dentro de su ciudad según el valor aleatorio
        $ranked = DB::query()
            ->fromSub($base, 'base')
            ->select('id')
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY ciudad ORDER BY rnd) as rn');

        // 3) Conserva solo los primeros N de cada sede
        $ids = DB::query()
            ->fromSub($ranked, 'ranked')
            ->where('rn', '<=', $porSede)
            ->pluck('id');

        if ($ids->isEmpty()) {
            return collect();
        }

        // 4) Hidrata los modelos con sus relaciones, mezclando sedes en pantalla
        return Inmueble::whereIn('id', $ids)
            ->with([
                'fotoInmueble' => function ($query) {
                    $query->where('posicion', 0);
                },
                'tipo_inmueble',
                'ciudadRelacion',
                'barrio',
            ])
            ->inRandomOrder()
            ->get();
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
        $ciudadesConOficina = Ciudad::contactPageCiudades()->load('contacts');

        $oficinasContacto = Ciudad::contactFormOfficeOptions();
        $oficinasUbicacion = Ciudad::contactOfficeLocations($ciudadesConOficina);

        $primeraCiudad = $ciudadesConOficina->first();
        $ubicacionInicial = $primeraCiudad
            ? ($oficinasUbicacion[$primeraCiudad->contactLocationKey()] ?? ['address' => '', 'map_embed' => '', 'phones' => [], 'phones_html' => 'Sin teléfono registrado.'])
            : ['address' => '', 'map_embed' => '', 'phones' => [], 'phones_html' => 'Sin teléfono registrado.'];

        return view('front.contact', compact(
            'ciudadesConOficina',
            'oficinasContacto',
            'oficinasUbicacion',
            'ubicacionInicial',
        ));
    }

    public function requirements()
    {
        return redirect()->route('tenant');
    }

    public function tenant()
    {
        return view('front.tenant');
    }

    public function property()
    {
        return view('front.property');
    }

    // Bonus: detalle propiedad
    public function show(Inmueble $inmueble)
    {
        return view('front.show', compact('inmueble'));
    }

}
