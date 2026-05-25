<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:150',
            'comments' => 'required|string|max:2000',
        ]);

        try {
            Mail::send('emails.contact', $data, function ($message) use ($data) {
                $message->to('sauloandres@gmail.com', 'Paseo España')
                        ->subject($data['subject']);
            });

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



        //return back()->with('success', 'Mensaje enviado correctamente.');
    }
}
