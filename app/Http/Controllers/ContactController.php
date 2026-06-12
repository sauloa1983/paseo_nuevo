<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $officeOptions = Ciudad::contactFormOfficeOptions()
            ->pluck('value')
            ->all();

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'oficina' => ['required', 'string', Rule::in($officeOptions)],
            'subject' => 'required|string|max:150',
            'comments' => 'required|string|max:2000',
        ], [
            'oficina.required' => 'Selecciona la oficina a la que deseas escribir.',
            'oficina.in' => 'La oficina seleccionada no es válida.',
        ]);

        $recipientEmail = Ciudad::resolveContactFormEmail($data['oficina']);
        $officeLabel = Ciudad::resolveContactFormLabel($data['oficina']);

        if (blank($recipientEmail) || blank($officeLabel)) {
            return response()->json([
                'success' => false,
                'message' => 'No encontramos un correo configurado para la oficina seleccionada. Intenta con otra sede o contáctanos por teléfono.',
            ], 422);
        }

        $mailData = [
            ...$data,
            'office' => $officeLabel,
        ];

        try {
            Mail::send('emails.contact', $mailData, function ($message) use ($mailData, $recipientEmail): void {
                $message->to($recipientEmail, 'Paseo España - '.$mailData['office'])
                    ->replyTo($mailData['email'], $mailData['name'])
                    ->subject('[Contacto web] '.$mailData['subject']);
            });

            return response()->json([
                'success' => true,
                'message' => 'Tu mensaje fue enviado correctamente a '.$officeLabel.'.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar: '.$e->getMessage(),
            ], 500);
        }
    }
}
