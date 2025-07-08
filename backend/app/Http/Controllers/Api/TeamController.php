<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = $request->user()->teams()->with(['owner', 'users', 'projects'])->get();
        return response()->json($teams);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $request->user()->id,
        ]);

        // Add the owner to the team
        $team->users()->attach($request->user()->id, ['role' => 'admin']);

        return response()->json($team->load(['owner', 'users']), 201);
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);
        return response()->json($team->load(['owner', 'users', 'projects.tasks']));
    }

    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($request->only(['name', 'description']));

        return response()->json($team->load(['owner', 'users']));
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);
        $team->delete();
        return response()->json(['message' => 'Team deleted successfully']);
    }

    public function addMember(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);
        
        if (!$team->users()->where('user_id', $user->id)->exists()) {
            $team->users()->attach($user->id);
        }

        return response()->json($team->load(['users']));
    }

    public function removeMember(Request $request, Team $team, User $user)
    {
        $this->authorize('update', $team);

        if ($team->owner_id === $user->id) {
            return response()->json(['error' => 'Cannot remove team owner'], 400);
        }

        $team->users()->detach($user->id);

        return response()->json($team->load(['users']));
    }
}
