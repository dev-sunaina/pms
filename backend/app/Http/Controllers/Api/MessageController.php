<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Team;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::with(['user', 'team']);
        
        // Filter by team if provided
        if ($request->has('team_id')) {
            $query->where('team_id', $request->team_id);
        }
        
        $messages = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'team_id' => 'required|exists:teams,id',
            'type' => 'sometimes|in:text,file,image',
        ]);

        $message = Message::create([
            'user_id' => $request->user()->id,
            'team_id' => $request->team_id,
            'message' => $request->message,
            'type' => $request->type ?? 'text',
        ]);

        return response()->json($message->load(['user', 'team']), 201);
    }

    public function show(Message $message)
    {
        return response()->json($message->load(['user', 'team']));
    }

    public function update(Request $request, Message $message)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'type' => 'sometimes|in:text,file,image',
        ]);

        $message->update($request->only(['message', 'type']));

        return response()->json($message->load(['user', 'team']));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(['message' => 'Message deleted successfully']);
    }

    public function teamMessages(Request $request, $teamId)
    {
        $messages = Message::where('team_id', $teamId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }
    public function getTeamMessages(Request $request, Team $team)
    {
        $this->authorize('view', $team);

        $messages = Message::where('team_id', $team->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }

    public function getProjectMessages(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $messages = Message::where('project_id', $project->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }

    public function sendTeamMessage(Request $request, Team $team)
    {
        $this->authorize('view', $team);

        $request->validate([
            'message' => 'required|string|max:1000',
            'type' => 'sometimes|in:text,file,image',
        ]);

        $message = Message::create([
            'user_id' => $request->user()->id,
            'team_id' => $team->id,
            'message' => $request->message,
            'type' => $request->type ?? 'text',
        ]);

        $message->load(['user']);

        // Broadcast the message to team members
        broadcast(new \App\Events\MessageSent($message, 'team', $team->id));

        return response()->json($message, 201);
    }

    public function sendProjectMessage(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $request->validate([
            'message' => 'required|string|max:1000',
            'type' => 'sometimes|in:text,file,image',
        ]);

        $message = Message::create([
            'user_id' => $request->user()->id,
            'project_id' => $project->id,
            'message' => $request->message,
            'type' => $request->type ?? 'text',
        ]);

        $message->load(['user']);

        // Broadcast the message to project members
        broadcast(new \App\Events\MessageSent($message, 'project', $project->id));

        return response()->json($message, 201);
    }

    public function deleteMessage(Request $request, Message $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        return response()->json(['message' => 'Message deleted successfully']);
    }
}
