<?php

namespace App\Policies;

use App\Models\Sticker;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StickerPolicy
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
    public function view(User $user, Sticker $sticker): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->can('place-stickers') ?
            Response::allow() :
            Response::deny('Je mag geen stickers plakken.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sticker $sticker): Response
    {
        if ($sticker->user()->is($user) || $user->can('manage-stickers')) {
            return Response::allow();
        } else {
            return Response::deny('Je mag deze sticker niet bewerken.');
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sticker $sticker): Response
    {
        if ($sticker->user()->is($user) || $user->can('manage-stickers')) {
            return Response::allow();
        } else {
            return Response::deny('Je mag deze sticker niet verwijderen.');
        }
    }
}
