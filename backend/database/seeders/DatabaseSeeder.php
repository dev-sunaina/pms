<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        $developer = User::create([
            'name' => 'Developer User',
            'email' => 'developer@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        // Create a team
        $team = Team::create([
            'name' => 'Development Team',
            'description' => 'Main development team for the project',
            'owner_id' => $admin->id,
        ]);

        // Add users to team
        $team->users()->attach([$admin->id, $manager->id, $developer->id]);

        // Create a project
        $project = Project::create([
            'name' => 'Project Management System',
            'description' => 'A comprehensive project management system built with Laravel and React',
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'team_id' => $team->id,
            'created_by' => $admin->id,
        ]);

        // Add users to project
        $project->users()->attach([$admin->id, $manager->id, $developer->id]);

        // Create some tasks
        Task::create([
            'title' => 'Setup Authentication System',
            'description' => 'Implement user authentication with Laravel Sanctum',
            'status' => 'completed',
            'priority' => 'high',
            'project_id' => $project->id,
            'assigned_to' => $developer->id,
            'created_by' => $manager->id,
            'due_date' => now()->subDays(5),
        ]);

        Task::create([
            'title' => 'Create Project Dashboard',
            'description' => 'Build a responsive dashboard for project overview',
            'status' => 'in_progress',
            'priority' => 'medium',
            'project_id' => $project->id,
            'assigned_to' => $developer->id,
            'created_by' => $manager->id,
            'due_date' => now()->addDays(7),
        ]);

        Task::create([
            'title' => 'Implement Task Management',
            'description' => 'Create CRUD operations for task management',
            'status' => 'todo',
            'priority' => 'high',
            'project_id' => $project->id,
            'assigned_to' => $developer->id,
            'created_by' => $manager->id,
            'due_date' => now()->addDays(14),
        ]);
    }
}
