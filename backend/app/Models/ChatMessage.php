<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'project_id',
        'message',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team for the message.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the project for the message.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
