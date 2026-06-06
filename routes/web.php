<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\PropertyContactController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;

Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/inmuebles', [PropertyController::class, 'search'])->name('inmuebles.search');
Route::get('/inmuebles/{inmueble}', [PropertyController::class, 'show'])->name('inmuebles.show');

Route::get('/nosotros', [FrontController::class, 'about'])->name('about');
Route::get('/servicios', [FrontController::class, 'services'])->name('services');
Route::get('/contacto', [FrontController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/clientes', [FrontController::class, 'clients'])->name('clients');
Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
Route::get('/requisitos', [FrontController::class, 'requirements'])->name('requirements');
Route::get('/requisitos/arrendatarios', [FrontController::class, 'tenant'])->name('tenant');
Route::get('/requisitos/propietarios', [FrontController::class, 'property'])->name('property');
Route::get('/faq', [FrontController::class, 'faq'])->name('faq');

Route::get('/inmuebles/ciudad/{ciudad}', [FrontController::class, 'porCiudad'])->name('inmuebles.ciudad');


Route::get('/barrios/{ciudad}', function ($ciudadId) {
    if (! \App\Models\Ciudad::esVisibleEnBuscador($ciudadId)) {
        return response()->json([]);
    }

    $barrios = DB::table('barrios')
        ->where('ciudad_fk', $ciudadId)
        ->orderBy('nombre')
        ->get(['codigo_barrio', 'nombre']);

    return response()->json($barrios);
});

Route::post('/property/contact', [PropertyContactController::class, 'send'])
    ->name('property.contact');


Route::get('/limpiar-sistema', function () {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('storage:link');

    return '¡Proyecto resubido y actualizado con éxito!';
});
/* Crea enlace simbólico para almacenamiento público (si es necesario) */
Route::get('/crear-storage-link', function () {
    $target = base_path('storage/app/public');
    $link = public_path('storage');

    if (is_link($link)) {
        return 'Ya existe el enlace simbólico public/storage';
    }

    if (file_exists($link)) {
        return 'public/storage existe como carpeta o archivo, bórralo primero';
    }

    symlink($target, $link);

    return 'Enlace simbólico creado correctamente';
});
