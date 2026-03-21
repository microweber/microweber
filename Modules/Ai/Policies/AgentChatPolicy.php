<?php

namespace Modules\Ai\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use MicroweberPackages\User\Models\User;
use Modules\Ai\Models\AgentChat;

class AgentChatPolicy
{
    use HandlesAuthorization;

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
    public function view(User $user, AgentChat $agentChat): bool
    {
        // Admin can view all chats
        if ($user->is_admin) {
            return true;
        }

        // Users can only view their own chats
        return $agentChat->user_id === $user->id;
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
    public function update(User $user, AgentChat $agentChat): bool
    {
        // Admin can update all chats
        if ($user->is_admin) {
            return true;
        }

        // Users can only update their own chats
        return $agentChat->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AgentChat $agentChat): bool
    {
        // Admin can delete all chats
        if ($user->is_admin) {
            return true;
        }

        // Users can only delete their own chats
        return $agentChat->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AgentChat $agentChat): bool
    {
        // Admin can restore all chats
        if ($user->is_admin) {
            return true;
        }

        // Users can only restore their own chats
        return $agentChat->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AgentChat $agentChat): bool
    {
        // Only admin can force delete
        return $user->is_admin;
    }
}
