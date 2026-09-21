<?php

namespace Tests\Feature\Admin;

use App\Support\AccessControl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\Concerns\InteractsWithAdminRbac;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use InteractsWithAdminRbac;
    use RefreshDatabase;

    public function test_super_admin_can_create_role_and_sync_permissions(): void
    {
        $admin = $this->createSuperAdmin();

        $response = $this->actingAs($admin)->post(route('admin.roles.store'), [
            'name' => 'manager',
            'permissions' => [
                AccessControl::PRODUCT_VIEW,
                AccessControl::PRODUCT_CREATE,
            ],
        ]);

        $response->assertRedirect(route('admin.roles.index'));

        $role = Role::findByName('manager', AccessControl::GUARD);

        $this->assertTrue($role->hasPermissionTo(AccessControl::DASHBOARD_VIEW));
        $this->assertTrue($role->hasPermissionTo(AccessControl::PRODUCT_CREATE));
    }

    public function test_super_admin_can_update_role_permissions(): void
    {
        $admin = $this->createSuperAdmin();
        $this->seedRbac();

        $role = Role::create([
            'name' => 'editor',
            'guard_name' => AccessControl::GUARD,
        ]);
        $role->syncPermissions([AccessControl::DASHBOARD_VIEW, AccessControl::PRODUCT_VIEW]);

        $response = $this->actingAs($admin)->put(route('admin.roles.update', $role), [
            'name' => 'editor-updated',
            'permissions' => [
                AccessControl::PRODUCT_VIEW,
                AccessControl::PRODUCT_UPDATE,
            ],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'editor-updated']);
        $this->assertTrue($role->fresh()->hasPermissionTo(AccessControl::PRODUCT_UPDATE));
    }

    public function test_super_admin_role_cannot_be_renamed(): void
    {
        $admin = $this->createSuperAdmin();
        $superAdminRole = Role::findByName(AccessControl::SUPER_ADMIN_ROLE, AccessControl::GUARD);

        $response = $this->actingAs($admin)->put(route('admin.roles.update', $superAdminRole), [
            'name' => 'renamed-role',
            'permissions' => [AccessControl::PRODUCT_VIEW],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertSame(AccessControl::SUPER_ADMIN_ROLE, $superAdminRole->fresh()->name);
    }

    public function test_super_admin_role_cannot_be_deleted(): void
    {
        $admin = $this->createSuperAdmin();
        $superAdminRole = Role::findByName(AccessControl::SUPER_ADMIN_ROLE, AccessControl::GUARD);

        $response = $this->actingAs($admin)->delete(route('admin.roles.destroy', $superAdminRole));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('roles', ['id' => $superAdminRole->id]);
    }

    public function test_roles_can_be_sorted_by_name(): void
    {
        $admin = $this->createSuperAdmin();

        Role::create([
            'name' => 'zzz-role',
            'guard_name' => AccessControl::GUARD,
        ]);

        Role::create([
            'name' => 'aaa-role',
            'guard_name' => AccessControl::GUARD,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.roles.index', [
            'sort' => 'name',
            'direction' => 'asc',
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['aaa-role', 'super-admin', 'zzz-role']);
    }
}
