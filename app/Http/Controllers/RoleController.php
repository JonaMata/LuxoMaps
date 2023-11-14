<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Inertia\Inertia;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $roles = Role::all();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Roles/Edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();

        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): \Inertia\Response
    {
        return Inertia::render('Roles/Show', [
            'role' => $role,
            'users' => \Auth::user()->can('manage-roles') ? User::all()->each->setAppends(['assigned_roles']) : null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): \Inertia\Response
    {
        return Inertia::render('Roles/Edit', [
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): \Illuminate\Http\RedirectResponse
    {
        $role->name = $request->name;
        $role->save();

        return redirect()->route('roles.edit', $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): \Illuminate\Http\RedirectResponse
    {
        $role->delete();

        return redirect()->route('roles.index');
    }

    public function assign(Role $role, User $user): \Illuminate\Http\RedirectResponse
    {
        if (!Auth::user()->roles->some(function (Role $userRole) use ($role) {
            return $userRole->hasAllPermissions($role->permissions);
        })) {
            abort(403, 'Je kan geen rollen toekennen met meer rechten dan jezelf hebt.');
        }
        $user->assignRole($role);

        return redirect()->route('roles.show', $role);
    }

    public function revoke(Role $role, User $user): \Illuminate\Http\RedirectResponse
    {
        if (!Auth::user()->roles->some(function (Role $userRole) use ($role) {
            return $userRole->hasAllPermissions($role->permissions);
        })) {
            abort(403, 'Je kan geen rollen ontnemen met meer rechten dan jezelf hebt.');
        }

        if ($role->hasPermissionTo('manage-roles') && \Auth::user()->id === $user->id) {
            abort(403, 'Je kan jezelf niet de rechten ontnemen om rollen te beheren.');
        }

        $user->removeRole($role);

        return redirect()->route('roles.show', $role);
    }
}
