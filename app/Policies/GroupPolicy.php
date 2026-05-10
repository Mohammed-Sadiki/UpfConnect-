<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
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
    public function view(User $user, Group $group): bool
    {
        // Groupes publics visibles par tous
        if ($group->visibility === 'public') {
            return true;
        }

        // Groupes privés visibles seulement par les membres
        return $group->isMember($user);
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
    public function update(User $user, Group $group): bool
    {
        return $group->isAdmin($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group): bool
    {
        return $group->created_by === $user->id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can moderate the group.
     */
    public function moderate(User $user, Group $group): bool
    {
        return $group->isModerator($user);
    }

    /**
     * Determine whether the user can admin the group.
     */
    public function admin(User $user, Group $group): bool
    {
        return $group->isAdmin($user);
    }

    /**
     * Determine whether the user can post in the group.
     */
    public function post(User $user, Group $group): bool
    {
        return $group->isMember($user);
    }
}
