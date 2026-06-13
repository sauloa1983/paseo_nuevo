<?php

namespace App\Http\Controllers;

use App\Mail\PropertyContactMail;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PropertyContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'property_id' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $property = Property::query()
            ->with('asesorData')
            ->findOrFail($request->property_id);

        $advisorEmail = $property->advisorEmail();
        $recipientEmail = mail_recipient($advisorEmail ?? '');

        if (blank($recipientEmail)) {
            return response()->json([
                'success' => false,
                'message' => 'Este inmueble no tiene un asesor con correo registrado. Usa el teléfono de contacto.',
            ], 422);
        }

        try {
            Mail::to($recipientEmail)->send(
                new PropertyContactMail(
                    $property,
                    $request->only('email', 'phone', 'message')
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'Tu mensaje fue enviado correctamente al asesor.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar: ' . $e->getMessage(),
            ], 500);
        }
    }
}
