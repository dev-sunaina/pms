<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TimesheetController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Team routes
    Route::apiResource('teams', TeamController::class);
    Route::post('/teams/{team}/members', [TeamController::class, 'addMember']);
    Route::delete('/teams/{team}/members/{user}', [TeamController::class, 'removeMember']);
    
    // Project routes
    Route::apiResource('projects', ProjectController::class);
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember']);
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember']);
    
    // Task routes
    Route::apiResource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
    
    // Timesheet routes
    Route::apiResource('timesheets', TimesheetController::class);
    Route::get('/timesheets/summary', [TimesheetController::class, 'summary']);
    
    // Message routes
    Route::apiResource('messages', MessageController::class);
    Route::get('/teams/{team}/messages', [MessageController::class, 'teamMessages']);
    
    // Additional routes for better API structure
    Route::get('/teams/{team}/projects', [ProjectController::class, 'teamProjects']);
    Route::get('/projects/{project}/tasks', [TaskController::class, 'projectTasks']);
    Route::get('/projects/{project}/timesheets', [TimesheetController::class, 'projectTimesheets']);
    Route::get('/users/{user}/timesheets', [TimesheetController::class, 'userTimesheets']);
});