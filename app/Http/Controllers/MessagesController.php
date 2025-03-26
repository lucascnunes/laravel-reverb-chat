<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessage;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(Message::with('user')->get()->append('time'));
    }

    /**
     * Store a new message sent to the chat
     */
    public function store(Request $request): void
    {
        $message = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $user = $request->user();

        $message = $user->messages()->create([
            'text' => $message['message'],
        ]);

        SendMessage::dispatch($message);
    }
}
