<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'description',
        'hours',
        'date',
        'start_time',
        'end_time',
        'is_billable',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:2',
        'is_billable' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the user that owns the timesheet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project for the timesheet.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the task for the timesheet.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }


}
