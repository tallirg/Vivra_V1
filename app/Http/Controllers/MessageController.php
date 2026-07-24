<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Obtener la lista de chats activos
    public function conversations()
    {
        $userId = auth()->id();
        
        // Buscamos usuarios con los que hemos intercambiado mensajes
        $contacts = User::whereHas('sentMessages', function($q) use ($userId) {
            $q->where('receiver_id', $userId);
        })->orWhereHas('receivedMessages', function($q) use ($userId) {
            $q->where('sender_id', $userId);
        })->get();

        $conversations = $contacts->map(function($contact) use ($userId) {
            $lastMessage = Message::where(function($q) use ($userId, $contact) {
                $q->where('sender_id', $userId)->where('receiver_id', $contact->id);
            })->orWhere(function($q) use ($userId, $contact) {
                $q->where('sender_id', $contact->id)->where('receiver_id', $userId);
            })->latest()->first();

            return [
                'contact_id' => $contact->id,
                'contact_name' => $contact->name ?? 'Usuario',
                'last_message' => $lastMessage ? $lastMessage->message : '',
            ];
        });

        return response()->json($conversations);
    }

    // Obtener los mensajes de un chat específico
    public function getMessages(Request $request)
    {
        // Por ahora leemos todos los mensajes del usuario para pruebas
        $messages = Message::where('sender_id', auth()->id())
                           ->orWhere('receiver_id', auth()->id())
                           ->orderBy('created_at', 'asc')
                           ->get();
        return response()->json($messages);
    }

    // Enviar un mensaje
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_name' => 'required|string'
        ]);

        // Buscamos al usuario por nombre (o ajustamos para mandar el ID luego)
        $receiver = User::where('name', $request->receiver_name)->first();

        if(!$receiver) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'message' => $request->message
        ]);

        return response()->json($message, 201);
    }
}