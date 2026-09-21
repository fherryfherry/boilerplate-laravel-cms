<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\AccessControl;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (AccessControl::allPermissions() as $permission) {
            Permission::findOrCreate($permission, AccessControl::GUARD);
        }

        $superAdmin = Role::findOrCreate(AccessControl::SUPER_ADMIN_ROLE, AccessControl::GUARD);
        $superAdmin->syncPermissions(AccessControl::allPermissions());

        $adminUser = User::query()->where('email', 'admin@example.com')->first();

        if ($adminUser) {
            $adminUser->syncRoles([$superAdmin]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
