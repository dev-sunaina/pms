<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()->projects()
            ->with(['team', 'createdBy', 'users', 'tasks'])
            ->get();
        
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'team_id' => 'required|exists:teams,id',
            'status' => 'sometimes|in:active,completed,on_hold,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
        ]);

        // Check if user is a member of the team
        $team = Team::find($request->team_id);
        if (!$team->users()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You are not a member of this team.'], 403);
        }

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'client_name' => $request->client_name,
            'team_id' => $request->team_id,
            'created_by' => $request->user()->id,
            'status' => $request->status ?? 'active',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'budget' => $request->budget,
        ]);

        // Add the creator to the project
        $project->users()->attach($request->user()->id);

        return response()->json($project->load(['team', 'createdBy', 'users']), 201);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return response()->json($project->load(['team', 'createdBy', 'users', 'tasks.assignedTo', 'timesheets.user']));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'status' => 'sometimes|in:active,completed,on_hold,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
        ]);

        $project->update($request->only([
            'name', 'description', 'client_name', 'status', 
            'start_date', 'end_date', 'budget'
        ]));

        return response()->json($project->load(['team', 'createdBy', 'users']));
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function addMember(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);
        
        if (!$project->users()->where('user_id', $user->id)->exists()) {
            $project->users()->attach($user->id);
        }

        return response()->json($project->load(['users']));
    }

    public function removeMember(Request $request, Project $project, User $user)
    {
        $this->authorize('update', $project);

        $project->users()->detach($user->id);

        return response()->json($project->load(['users']));
    }
}
