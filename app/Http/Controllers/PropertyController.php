<?php
namespace App\Http\Controllers;

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


        $ciudades = DB::table('ciudades')
            ->orderBy('nombre')
            ->pluck('nombre', 'id');


        $query = Property::query()->where('estado', 0);  // ← Primero (más selectivo)

            // Defino variables
            $precioCampo = $request->status == 'rent' ? 'valor_arriendo' : 'valor_venta';
            $sumarAdmin  = false; // valor por defecto

            // 🔍 FILTRO STATUS (rent/sale)
            if ($request->filled('status')) {
                if ($request->status === 'rent') {
                    $query->where('arriendo', 1);
                    $precioCampo = 'valor_arriendo';
                    $sumarAdmin = true;
                } elseif ($request->status === 'sale') {
                    $query->where('venta', 1);
                    $precioCampo = 'valor_venta';
                    $sumarAdmin = false;
                }
            }

            // Min/Max precio (limpios)
            $min_price = $request->filled('min_price')
                ? (int) str_replace('.', '', $request->min_price)
                : null;
            $max_price = $request->filled('max_price')
                ? (int) str_replace('.', '', $request->max_price)
                : null;

            // Filtros precio (con/sin admin)
            if ($min_price) {
                if ($sumarAdmin) {
                    // ARRIENDO: valor_arriendo + admin >= min_price
                    $query->whereRaw("(valor_arriendo + COALESCE(administracion, 0)) >= ?", [$min_price]);
                } else {
                    $query->where($precioCampo, '>=', $min_price);
                }
            }

            if ($max_price) {
                if ($sumarAdmin) {
                    // ARRIENDO: valor_arriendo + admin <= max_price
                    $query->whereRaw("(valor_arriendo + COALESCE(administracion, 0)) <= ?", [$max_price]);
                } else {
                    $query->where($precioCampo, '<=', $max_price);
                }
            }

            /////////////////////////////////////////////////////////////////////////////////////////////////////////

            //Busca por ciudad
            if ($request->filled('ciudad')) {
                $query->where('ciudad', $request->ciudad);  // ← Aquí sí usa el valor 3
            }
            //Busca por barrio
            if ($request->filled('barrio')) {
                $query->where('barrio_fk', $request->barrio);
            }
            //Busca por tipo inmueble
            if ($request->filled( 'type')) {
                $query->where('tipo_fk', $request->type);
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
                $query->orderByRaw('COALESCE(valor_arriendo + administracion, valor_arriendo) ASC');
            } else {
                $query->orderBy('valor_venta', 'asc');
            }
            } elseif ($order === 'precio_desc') {
            if ($request->status == 'rent') {
                $query->orderByRaw('COALESCE(valor_arriendo + administracion, valor_arriendo) DESC');
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
            ->leftJoin('usuarios', 'inmuebles.asesor', '=', 'usuarios.cedula')
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
        $layout = $request->input('layout', 'list'); // predeterminado: list

        return view('front.inmuebles', compact('properties', 'tipos_inm', 'ciudades', 'layout'));
    }

    public function show($inmueble)
    {
         ////////// INMUEBLES SIMILARES ///////////
        $property = Property::where('codigo', $inmueble)->firstOrFail();

        $property_val = $property->arriendo == "1"
            ? 'arriendo'
            : ($property->venta == "1" ? 'venta' : null);

        $property_price = $property->arriendo == "1"
            ? 'valor_arriendo'
            : ($property->venta == "1" ? 'valor_venta' : null);

        if (!$property_val || !$property_price) {
            // Evita errores si no hay ni arriendo ni venta definidos
            return view('front.inmuebles.show', compact('property', 'similarProperties'));
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

        return view('front.inmuebles.show', compact('property','similarProperties', 'property_featured'));
    }

}
