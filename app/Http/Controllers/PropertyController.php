<?php
namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\PromotionalVideo;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\alert;

class PropertyController extends Controller
{
    public function search(Request $request)
    {
        $tipos_inm = DB::table('tipo_inmueble')
            ->select('id','tipo')
            ->orderBy('tipo')
            ->pluck('tipo','id');


        $ciudades = Ciudad::query()
            ->visibleInBuscador()
            ->orderBy('nombre')
            ->pluck('nombre', 'id');


        $query = Property::query()
            ->where('estado', 0)        // ← Primero (más selectivo)
            ->conDatosCompletos();      // Excluye inmuebles "N/A" (sin tipo ni precio válido)

            // Defino variables
            $precioCampo = $request->status == 'rent' ? 'valor_arriendo' : 'valor_venta';

            // 🔍 FILTRO STATUS (rent/sale)
            if ($request->filled('status')) {
                if ($request->status === 'rent') {
                    $query->where('arriendo', 1);
                    $query->where('valor_arriendo', '>', 0); // precio de arriendo válido
                    $precioCampo = 'valor_arriendo';
                } elseif ($request->status === 'sale') {
                    $query->where('venta', 1);
                    $query->where('valor_venta', '>', 0); // precio de venta válido
                    $precioCampo = 'valor_venta';
                }
            }

            // Min/Max precio (limpios). Un 0 o vacío se considera "sin filtro".
            $min_price = $request->filled('min_price')
                ? (int) str_replace('.', '', $request->min_price)
                : null;
            $max_price = $request->filled('max_price')
                ? (int) str_replace('.', '', $request->max_price)
                : null;

            // Evita que un 0 que llegue por la URL rompa el filtrado.
            if ($min_price !== null && $min_price <= 0) {
                $min_price = null;
            }
            if ($max_price !== null && $max_price <= 0) {
                $max_price = null;
            }

            // Filtro por precio. La administración NUNCA se suma al precio principal.
            if ($min_price !== null || $max_price !== null) {
                if ($request->filled('status')) {
                    // Con status definido: filtra sobre el campo de precio correspondiente.
                    if ($min_price !== null) {
                        $query->where($precioCampo, '>=', $min_price);
                    }
                    if ($max_price !== null) {
                        $query->where($precioCampo, '<=', $max_price);
                    }
                } else {
                    // Sin status: el rango debe coincidir con arriendo O con venta.
                    $query->where(function ($q) use ($min_price, $max_price) {
                        $q->where(function ($sub) use ($min_price, $max_price) {
                            $sub->where('valor_arriendo', '>', 0);
                            if ($min_price !== null) {
                                $sub->where('valor_arriendo', '>=', $min_price);
                            }
                            if ($max_price !== null) {
                                $sub->where('valor_arriendo', '<=', $max_price);
                            }
                        })->orWhere(function ($sub) use ($min_price, $max_price) {
                            $sub->where('valor_venta', '>', 0);
                            if ($min_price !== null) {
                                $sub->where('valor_venta', '>=', $min_price);
                            }
                            if ($max_price !== null) {
                                $sub->where('valor_venta', '<=', $max_price);
                            }
                        });
                    });
                }
            }

            // 🔍 FILTRO ADMINISTRACIÓN (con / sin administración)
            if ($request->filled('admon')) {
                if ($request->admon === 'con') {
                    $query->where('administracion', '>', 0);
                } elseif ($request->admon === 'sin') {
                    $query->where(function ($q) {
                        $q->whereNull('administracion')->orWhere('administracion', 0);
                    });
                }
            }

            /////////////////////////////////////////////////////////////////////////////////////////////////////////

            // Busca por ciudad (solo ciudades visibles en el buscador público)
            if ($request->filled('ciudad') && Ciudad::esVisibleEnBuscador($request->ciudad)) {
                $query->where('ciudad', (int) $request->ciudad);
            }
            //Busca por barrio
            if ($request->filled('barrio')) {
                $query->where('barrio_fk', $request->barrio);
            }
            // Busca por uno o varios tipos de inmueble
            $typeIds = array_filter(array_map('intval', (array) $request->input('type', [])));
            if (! empty($typeIds)) {
                $query->whereIn('tipo_fk', $typeIds);
            }
            //Busca por habitaciones
            $rooms = $request->rooms;
            if (!empty($rooms)) {
                $query->where('no_alcobas', $rooms);
            }
            //Busca por baños
            $bathrooms = $request->bathrooms;
            if (!empty($bathrooms)) {
                $query->where('no_banos', $bathrooms);
            }
            // Busca por parqueadero (campo garajes = número de cupos)
            if ($request->filled('garaje')) {
                if ($request->garaje === '0') {
                    // Sin parqueadero
                    $query->where(function ($q) {
                        $q->whereNull('garajes')->orWhere('garajes', 0);
                    });
                } else {
                    // N cupos o más
                    $query->where('garajes', '>=', (int) $request->garaje);
                }
            }
            // Buscar por área mínima y máxima
            $min_area = $request->filled('min_area')
                ? (int) preg_replace('/\D/', '', $request->min_area)
                : null;

            $max_area = $request->filled('max_area')
                ? (int) preg_replace('/\D/', '', $request->max_area)
                : null;

            // Filtro por área (area_total o area_construida, según tu tabla)
            if ($min_area && $max_area) {
                $query->whereBetween('area_construida', [$min_area, $max_area]);
            } elseif ($min_area) {
                $query->where('area_construida', '>=', $min_area);
            } elseif ($max_area) {
                $query->where('area_construida', '<=', $max_area);
            }

            //Busca por baños
            $estrato = $request->estrato;
            if (!empty($estrato)) {
                $query->where('estrato', $estrato);
            }

            // Filtros por checkboxes
            if ($request->filled('ascensor')) {
                $query->where('ascensor', 1);
            }

            if ($request->filled('piscina')) {
                $query->where('piscina', 1);
            }

            if ($request->filled('conjunto_cerrado')) {
                $query->where('conjunto_cerrado', 1);
            }

            if ($request->filled('alcoba_servicio')) {
                $query->where('alcoba_servicio', 1);
            }

            if ($request->filled('gimnasio')) {
                $query->where('gimnasio', 1);
            }

            if ($request->filled('salon_social')) {
                $query->where('salon_social', 1);
            }

            if ($request->filled('balcon')) {
                $query->where('balcon', 1);
            }

            // Ordenamiento
            $order = $request->input('order');

            if ($order === 'precio_asc') {
            if ($request->status == 'rent') {
                $query->orderBy('valor_arriendo', 'asc');
            } else {
                $query->orderBy('valor_venta', 'asc');
            }
            } elseif ($order === 'precio_desc') {
            if ($request->status == 'rent') {
                $query->orderBy('valor_arriendo', 'desc');
            } else {
                $query->orderBy('valor_venta', 'desc');
            }
            } else {
            $query->orderBy('id', 'desc');
            }



            // 🔍 VER SQL ANTES PAGINATE
            /*dump($query->toSql());
            dump($query->getBindings());*/

        $properties = $query
            ->leftJoin('usuarios', 'inmuebles.asesor', '=', 'usuarios.id')
            ->select(
                'inmuebles.*',
                'usuarios.nombres as asesor_nombres',
                'usuarios.apellidos as asesor_apellidos',
                'usuarios.telefonos as asesor_telefonos'
            )
            ->with(['fotos', 'ciudadRelacion', 'tipo_inmueble', 'barrio'])
            ->paginate(12)
            ->withQueryString();

        // 🔍 VER SQL DESPUES PAGINATE
        /*dump($query->toSql());
        dump($query->getBindings());*/

        /*dd([
            'primer_property' => $properties->first(),
            'asesor_tipo' => gettype($properties->first()->asesor),
            'asesor_datos' => $properties->first()->asesor
        ]);*/

        //Forma de mostrar el layaout
        $layout = $request->input('layout', 'grid-three');

        return view('front.inmuebles', compact('properties', 'tipos_inm', 'ciudades', 'layout'));
    }

    public function show($inmueble)
    {
         ////////// INMUEBLES SIMILARES ///////////
        $property = Property::where('codigo', $inmueble)
            ->with('asesorData')
            ->firstOrFail();

        $property_val = $property->arriendo == "1"
            ? 'arriendo'
            : ($property->venta == "1" ? 'venta' : null);

        $property_price = $property->arriendo == "1"
            ? 'valor_arriendo'
            : ($property->venta == "1" ? 'valor_venta' : null);

        if (!$property_val || !$property_price) {
            $similarProperties = collect();
            $promotionalVideo = PromotionalVideo::query()
                ->where('is_active', true)
                ->inRandomOrder()
                ->first();

            return view('front.inmuebles.show', compact('property', 'similarProperties', 'promotionalVideo'));
        }
        // Rango de precio (por ejemplo, ±20%)
        $minPrice = $property->$property_price * 0.8;
        $maxPrice = $property->$property_price * 1.2;

        $similarProperties = Property::where('tipo_fk', $property->tipo_fk)
            ->where('ciudad', $property->ciudad)
            ->where($property_val, 1) //Busca arriendo o venta
            ->where('id', '!=', $property->id)
            ->where('estado', 0)
            ->whereBetween($property_price, [$minPrice, $maxPrice])
            ->inRandomOrder()
            ->limit(3)
            ->with('fotos')
            ->get();

        //dd($similarProperties->toSql()); //veo la consulta basica
        //dd($similarProperties); //veo que trae toda la consulta

        $property_featured = Property::where('destacado', 1)->where('estado', 0)
            ->with([
                'fotos' => function($query) {
                    $query->where('posicion', 0);
                },
                'tipo_inmueble',
                'ciudad',
                'barrio',

            ])
            //->inRandomOrder() // Para mostrar diferentes destacados cada vez
            ->limit(6) //12
            ->get();
        //dd($property_featured);

        $promotionalVideo = PromotionalVideo::query()
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        return view('front.inmuebles.show', compact('property', 'similarProperties', 'property_featured', 'promotionalVideo'));
    }

}
