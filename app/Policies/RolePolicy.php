<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->can('view-roles') ?
            Response::allow() :
            Response::deny('Je hebt niet de rechten om rollen te kunnen zien.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): Response
    {
        return $user->can('view-roles') ?
            Response::allow() :
            Response::deny('Je hebt niet de rechten om rollen te kunnen zien.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Response::deny('Rollen kunnen niet aangemaakt worden.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): Response
    {
        return Response::deny('Rollen kunnen niet aangepast worden');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): Response
    {
        return Response::deny('Rollen kunnen niet verwijderd worden.');
    }
}
