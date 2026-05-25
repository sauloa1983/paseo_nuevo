<?php

namespace App\Http\Controllers;

use App\Mail\PropertyContactMail;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PropertyContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'property_id' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $property = Property::findOrFail($request->property_id);

        try {
            Mail::to('sauloandres@gmail.com')->send(
                new PropertyContactMail(
                    $property,
                    $request->only('email', 'phone', 'message')
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'Tu mensaje fue enviado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar: ' . $e->getMessage()
            ], 500);
        }
    }
}


/*
public function send(Request $request)
    {
        $request->validate([
            'property_id' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $property = Property::findOrFail($request->property_id);

        try {
            Mail::raw(
                "Nuevo contacto\n\n".
                "Property ID: ".$property->id."\n".
                "Email cliente: ".$request->email."\n".
                "Teléfono: ".$request->phone."\n".
                "Mensaje: ".$request->message,
                function ($message) {
                    $message->to('sauloandres@gmail.com')
                            ->subject('Prueba formulario propiedad');
                }
            );

            return back()->with('success', 'Correo enviado correctamente desde el formulario.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar: '.$e->getMessage());
        }
    }*/
