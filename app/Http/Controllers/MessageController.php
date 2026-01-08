<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get all users except current
        $users = User::where('id', '!=', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Get conversations
        $conversations = Message::getConversations($user->id);
        
        return view('messages.index', compact('users', 'conversations'));
    }

    public function unreadCount()
    {
        $count = Message::unreadCountFor(auth()->id());
        
        return response()->json(['count' => $count]);
    }

    public function conversation(User $user)
    {
        $currentUser = auth()->user();
        
        $messages = Message::betweenUsers($currentUser->id, $user->id)
            ->orderBy('created_at')
            ->get();
        
        // Mark as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json([
            'messages' => $messages->map(fn($m) => [
                'id' => $m->id,
                'content' => $m->content,
                'is_mine' => $m->sender_id === $currentUser->id,
                'is_read' => $m->is_read,
                'created_at' => $m->created_at->setTimezone('Asia/Jakarta')->format('H:i'),
                'date' => $m->created_at->setTimezone('Asia/Jakarta')->format('d M'),
                'created_at_raw' => $m->created_at->toIso8601String(),
            ]),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar_url,
            ],
        ]);
    }

    public function send(Request $request, User $user)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'content' => $validated['content'],
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'is_mine' => true,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    public function markRead(User $user)
    {
        Message::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
