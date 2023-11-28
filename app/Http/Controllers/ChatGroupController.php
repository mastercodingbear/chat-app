<?php

namespace App\Http\Controllers;

use App\Models\ChatGroup;
use App\Models\User;
use Illuminate\Http\Request;

class ChatGroupController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:chat_groups',
        ]);

        $chatGroup = ChatGroup::create([
            'name' => $request->name,
        ]);

        return response()->json(['chat_group_id' => $chatGroup->id]);
    }

    public function join(Request $request, $groupId)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = User::where('token', $request->token)->first();
        $chatGroup = ChatGroup::findOrFail($groupId);

        $chatGroup->users()->attach($user);

        return response()->json(['message' => 'Successfully joined the group']);
    }

    public function listGroups()
    {
        $chatGroups = ChatGroup::all();

        return response()->json(['chat_groups' => $chatGroups]);
    }

    public function leave(Request $request, $groupId)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = User::where('token', $request->token)->first();
        $chatGroup = ChatGroup::findOrFail($groupId);

        $chatGroup->users()->detach($user);

        return response()->json(['message' => 'Successfully left the group']);
    }
}
