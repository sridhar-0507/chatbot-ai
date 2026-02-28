<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /* ===============================
        Landing Page
    =============================== */
    public function landing()
    {
        return view('welcome');
    }

    /* ===============================
        GPT Chat Page
    =============================== */
    public function chat($session_id = null)
    {
        $sessions = ChatSession::where('type', 'gpt')
            ->latest()
            ->get();

        $currentSession = null;

        if ($session_id) {
            $currentSession = ChatSession::where('session_id', $session_id)
                ->where('type', 'gpt')
                ->first();
        }

        return view('chat', compact('sessions', 'currentSession'));
    }

    /* ===============================
        Send Message (GPT + Widget)
    =============================== */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'type' => 'required|in:gpt,widget',
            'session_id' => 'nullable|string'
        ]);

        $message = $request->message;
        $type = $request->type;
        $sessionId = $request->session_id;

        /* Create session only after first message */
       if (!$sessionId) {

    $session = ChatSession::create([
        'session_id' => Str::uuid(),
        'type' => $type,
        'title' => Str::limit($message, 40) // auto title
    ]);

    $sessionId = $session->session_id;
}else {
            $session = ChatSession::where('session_id', $sessionId)->first();
        }

        /* Save User Message */
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'role' => 'user',
            'message' => $message
        ]);

        /* Build history */
        $history = ChatMessage::where('chat_session_id', $session->id)
            ->orderBy('created_at')
            ->get()
            ->map(function ($msg) {
                return [
                    'role' => $msg->role,
                    'content' => $msg->message
                ];
            })
            ->toArray();

        /* Call GROQ */
       $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => $history,
            'stream' => false, // true only if using SSE properly
        ]);
        $assistantReply = data_get($response->json(), 'choices.0.message.content');

        if (!$assistantReply) {
            $assistantReply = "AI is thinking... please try again.";
        }

        /* Save Assistant */
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'role' => 'assistant',
            'message' => $assistantReply
        ]);

        return response()->json([
            'reply' => $assistantReply,
            'session_id' => $sessionId
        ]);
    }

    /* ===============================
        Delete Chat
    =============================== */
    public function delete($session_id)
    {
        $session = ChatSession::where('session_id', $session_id)->first();

        if ($session) {
            $session->delete();
        }

        return redirect()->route('chat.page');
    }
    public function rename(Request $request, $session_id)
{
    $request->validate([
        'title' => 'required|string|max:255'
    ]);

    $session = ChatSession::where('session_id', $session_id)->firstOrFail();

    $session->update([
        'title' => $request->title
    ]);

    return response()->json([
        'success' => true,
        'title' => $session->title
    ]);
}
}