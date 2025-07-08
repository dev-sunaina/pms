<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $query = Timesheet::with(['user', 'project', 'task']);

        // Filter by user (default to current user)
        $userId = $request->get('user_id', $request->user()->id);
        $query->where('user_id', $userId);

        // Filter by project if provided
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by date range if provided
        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // Default to current week if no date filters
        if (!$request->has('start_date') && !$request->has('end_date')) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
        }

        $timesheets = $query->orderBy('date', 'desc')->get();
        
        return response()->json($timesheets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'description' => 'nullable|string',
            'hours' => 'required|numeric|min:0.1|max:24',
            'date' => 'required|date',
            'billable' => 'sometimes|boolean',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $timesheet = Timesheet::create([
            'user_id' => $request->user()->id,
            'project_id' => $request->project_id,
            'task_id' => $request->task_id,
            'description' => $request->description,
            'hours' => $request->hours,
            'date' => $request->date,
            'billable' => $request->billable ?? true,
            'hourly_rate' => $request->hourly_rate,
        ]);

        return response()->json($timesheet->load(['user', 'project', 'task']), 201);
    }

    public function show(Timesheet $timesheet)
    {
        $this->authorize('view', $timesheet);
        return response()->json($timesheet->load(['user', 'project', 'task']));
    }

    public function update(Request $request, Timesheet $timesheet)
    {
        $this->authorize('update', $timesheet);

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'description' => 'nullable|string',
            'hours' => 'required|numeric|min:0.1|max:24',
            'date' => 'required|date',
            'billable' => 'sometimes|boolean',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $timesheet->update($request->only([
            'project_id', 'task_id', 'description', 'hours', 
            'date', 'billable', 'hourly_rate'
        ]));

        return response()->json($timesheet->load(['user', 'project', 'task']));
    }

    public function destroy(Timesheet $timesheet)
    {
        $this->authorize('delete', $timesheet);
        $timesheet->delete();
        return response()->json(['message' => 'Timesheet entry deleted successfully']);
    }

    public function summary(Request $request)
    {
        $query = Timesheet::where('user_id', $request->user()->id);

        // Filter by date range if provided
        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $summary = $query->selectRaw('
            SUM(hours) as total_hours,
            SUM(CASE WHEN billable = 1 THEN hours ELSE 0 END) as billable_hours,
            SUM(CASE WHEN billable = 0 THEN hours ELSE 0 END) as non_billable_hours,
            SUM(hours * hourly_rate) as total_amount
        ')->first();

        return response()->json($summary);
    }
}
