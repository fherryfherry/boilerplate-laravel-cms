<?php

namespace Tests\Concerns;

use App\Models\User;
use App\Support\AccessControl;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

trait InteractsWithAdminRbac
{
    protected function seedRbac(): void
    {
        $this->seed(RolePermissionSeeder::class);
    }

    protected function createSuperAdmin(): User
    {
        $this->seedRbac();

        $user = User::factory()->create();
        $user->assignRole(AccessControl::SUPER_ADMIN_ROLE);

        return $user;
    }

    protected function createUserWithPermissions(array $permissions): User
    {
        $this->seedRbac();

        $role = Role::create([
            'name' => 'role-'.Str::lower(Str::random(10)),
            'guard_name' => AccessControl::GUARD,
        ]);

        $role->syncPermissions(array_unique(array_merge([AccessControl::DASHBOARD_VIEW], $permissions)));

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
