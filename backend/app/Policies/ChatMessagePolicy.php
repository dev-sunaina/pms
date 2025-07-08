<?php

namespace App\Policies;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChatMessage $chatMessage): bool
    {
        if ($chatMessage->team_id) {
            return $chatMessage->team->users()->where('user_id', $user->id)->exists();
        }
        
        if ($chatMessage->project_id) {
            return $chatMessage->project->users()->where('user_id', $user->id)->exists() || 
                   $chatMessage->project->team->users()->where('user_id', $user->id)->exists();
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChatMessage $chatMessage): bool
    {
        return $chatMessage->user_id === $user->id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChatMessage $chatMessage): bool
    {
        return $chatMessage->user_id === $user->id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChatMessage $chatMessage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChatMessage $chatMessage): bool
    {
        return false;
    }
}
