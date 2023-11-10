<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::findOrCreate('view-roles')->update(['description' => 'View roles']);
        Permission::findOrCreate('manage-roles')->update(['description' => 'Manage roles']);
        Permission::findOrCreate('view-permissions')->update(['description' => 'View permissions']);
        Permission::findOrCreate('view-peertjes')->update(['description' => 'View peertjes']);
        Permission::findOrCreate('manage-peertjes')->update(['description' => 'Manage peertjes']);

        // Create roles
        $admin = Role::findOrCreate('admin');
        $admin->update(['description' => 'Admin']);
        $admin->givePermissionTo(
            Permission::all()
        );
        $lid = Role::findOrCreate('lid');
        $lid->update(['description' => 'Lid']);
        $lid->givePermissionTo([
            'view-roles',
            'view-permissions',
            'view-peertjes',
        ]);
    }
}
