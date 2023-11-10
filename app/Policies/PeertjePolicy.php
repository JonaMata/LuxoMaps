<?php

namespace App\Policies;

use App\Models\Peertje;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PeertjePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->can('view-peertjes') ?
            Response::allow() :
            Response::deny('Je hebt niet de rechten om peertjes te kunnen zien.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Peertje $peertje): Response
    {
        return $user->can('view-peertjes') ?
            Response::allow() :
            Response::deny('Je hebt niet de rechten om peertjes te kunnen zien.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->can('manage-peertjes') ?
            Response::allow() :
            Response::deny('Je hebt niet de rechten om peertjes aan te maken.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Peertje $peertje): Response
    {
        return $user->can('manage-peertjes') ?
            Response::allow() :
            Response::deny('Je hebt niet de rechten om peertjes te bewerken.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Peertje $peertje): Response
    {
        return $user->can('manage-peertjes') ?
            Response::allow() :
            Response::deny('Je hebt niet de rechten om peertjes te verwijderen.');
    }
}
