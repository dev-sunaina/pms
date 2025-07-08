<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignedUser', 'creator', 'timesheets']);

        // Filter by project if provided
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by assigned user if provided
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->get();
        
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'sometimes|in:todo,in_progress,review,completed',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => $request->user()->id,
            'status' => $request->status ?? 'todo',
            'priority' => $request->priority ?? 'medium',
            'due_date' => $request->due_date,
            'estimated_hours' => $request->estimated_hours,
        ]);

        return response()->json($task->load(['project', 'assignedUser', 'creator']), 201);
    }

    public function show(Task $task)
    {
        // $this->authorize('view', $task);
        return response()->json($task->load(['project', 'assignedUser', 'creator', 'timesheets.user']));
    }

    public function update(Request $request, Task $task)
    {
        // $this->authorize('update', $task);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'sometimes|in:todo,in_progress,review,completed',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
        ]);

        $task->update($request->only([
            'title', 'description', 'assigned_to', 'status', 
            'priority', 'due_date', 'estimated_hours', 'actual_hours'
        ]));

        return response()->json($task->load(['project', 'assignedUser', 'creator']));
    }

    public function destroy(Task $task)
    {
        // $this->authorize('delete', $task);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function updateStatus(Request $request, Task $task)
    {
        // $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:todo,in_progress,review,completed',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json($task->load(['project', 'assignedUser', 'creator']));
    }
}
