<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ChatGroup;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $request, $groupId)
    {
        $request->validate([
            'token' => 'required|string',
            'content' => 'required|string',
        ]);

        $user = User::where('token', $request->token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $chatGroup = ChatGroup::findOrFail($groupId);

        if (!$chatGroup) {
            return response()->json(['error' => 'Chat group not found'], 404);
        }

        // Validate if the user is a member of the chat group
        if (!$chatGroup->users->contains($user)) {
            return response()->json(['error' => 'User is not a member of this chat group'], 403);
        }

        $message = Message::create([
            'user_id' => $user->id,
            'chat_group_id' => $chatGroup->id,
            'content' => $request->content,
        ]);

        return response()->json(['message_id' => $message->id]);
    }

    public function list($groupId)
    {
        $chatGroup = ChatGroup::findOrFail($groupId);

        if (!$chatGroup) {
            return response()->json(['error' => 'Chat group not found'], 404);
        }

        $messages = $chatGroup->messages()->with('user')->get();

        return response()->json(['messages' => $messages]);
    }
}
