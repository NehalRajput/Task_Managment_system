<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser instanceof Admin;
        
        if ($isAdmin) {
            $users = User::whereHas('role', function($query) {
                $query->where('name', 'intern');
            })->get();
            $viewData = ['interns' => $users, 'admins' => collect()];
        } else {
            $admins = Admin::all();
            $viewData = ['interns' => collect(), 'admins' => $admins];
        }

        return view('chat.index', $viewData);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $sender = Auth::user();
        $receiver = $request->receiver_type === 'admin' 
            ? Admin::find($request->receiver_id)
            : User::find($request->receiver_id);

        $message = new Message([
            'message' => $request->message,
        ]);

        $message->sender()->associate($sender);
        $message->receiver()->associate($receiver);
        $message->save();

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => $message->load('sender', 'receiver')
        ]);
    }

    public function getMessages(Request $request)
    {
        $currentUser = Auth::user();
        $otherUser = $request->route('user');

        $messages = Message::where(function($query) use ($currentUser, $otherUser) {
            $query->where('sender_id', $currentUser->id)
                  ->where('sender_type', get_class($currentUser))
                  ->where('receiver_id', $otherUser->id)
                  ->where('receiver_type', get_class($otherUser));
        })->orWhere(function($query) use ($currentUser, $otherUser) {
            $query->where('sender_id', $otherUser->id)
                  ->where('sender_type', get_class($otherUser))
                  ->where('receiver_id', $currentUser->id)
                  ->where('receiver_type', get_class($currentUser));
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id'
        ]);

        $message = Message::find($request->message_id);
        $message->read_at = now();
        $message->save();

        return response()->json(['status' => 'success']);
    }
}
