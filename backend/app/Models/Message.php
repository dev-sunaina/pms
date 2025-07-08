<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'message',
        'type',
        'file_path',
    ];

    /**
     * Get the user who sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team the message belongs to.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
