<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $teams = $user->teams()->with(['owner', 'users'])->get();
        
        return response()->json($teams);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id(),
        ]);

        // Add the creator as an admin member
        $team->users()->attach(Auth::id(), ['role' => 'admin']);

        return response()->json($team->load(['owner', 'users']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        // Check if user is a member of the team
        if (!$team->users->contains(Auth::id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($team->load(['owner', 'users', 'projects']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        // Check if user is the owner or admin
        $userRole = $team->users()->where('user_id', Auth::id())->first()?->pivot->role;
        if ($team->owner_id !== Auth::id() && $userRole !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($request->only(['name', 'description']));

        return response()->json($team->load(['owner', 'users']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        // Only owner can delete the team
        if ($team->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $team->delete();

        return response()->json(['message' => 'Team deleted successfully']);
    }

    /**
     * Join a team
     */
    public function join(Request $request, Team $team)
    {
        $user = Auth::user();
        
        if ($team->users->contains($user->id)) {
            return response()->json(['message' => 'Already a member of this team'], 400);
        }

        $team->users()->attach($user->id, ['role' => 'member']);

        return response()->json(['message' => 'Successfully joined the team']);
    }

    /**
     * Leave a team
     */
    public function leave(Team $team)
    {
        $user = Auth::user();
        
        if ($team->owner_id === $user->id) {
            return response()->json(['message' => 'Owner cannot leave the team'], 400);
        }

        $team->users()->detach($user->id);

        return response()->json(['message' => 'Successfully left the team']);
    }

    /**
     * Get team members
     */
    public function members(Team $team)
    {
        // Check if user is a member of the team
        if (!$team->users->contains(Auth::id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($team->users);
    }
}
