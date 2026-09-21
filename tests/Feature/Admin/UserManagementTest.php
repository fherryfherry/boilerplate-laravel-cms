<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Support\AccessControl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\Concerns\InteractsWithAdminRbac;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use InteractsWithAdminRbac;
    use RefreshDatabase;

    public function test_super_admin_can_create_user_and_assign_role(): void
    {
        $admin = $this->createSuperAdmin();

        $this->seedRbac();
        Role::create([
            'name' => 'manager',
            'guard_name' => AccessControl::GUARD,
        ])->syncPermissions([AccessControl::DASHBOARD_VIEW, AccessControl::PRODUCT_VIEW]);

        $this->actingAs($admin)
            ->get(route('admin.users.create'))
            ->assertOk();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'manager',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'manager@example.com']);
        $this->assertTrue(User::where('email', 'manager@example.com')->firstOrFail()->hasRole('manager'));
    }

    public function test_super_admin_can_update_user_role(): void
    {
        $admin = $this->createSuperAdmin();
        $user = User::factory()->create();

        $this->seedRbac();
        Role::create([
            'name' => 'editor',
            'guard_name' => AccessControl::GUARD,
        ])->syncPermissions([AccessControl::DASHBOARD_VIEW, AccessControl::PRODUCT_UPDATE]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => 'Edited User',
            'email' => 'edited@example.com',
            'password' => '',
            'password_confirmation' => '',
            'role' => 'editor',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertTrue($user->fresh()->hasRole('editor'));
        $this->assertSame('edited@example.com', $user->fresh()->email);
    }

    public function test_super_admin_can_delete_non_system_user(): void
    {
        $admin = $this->createSuperAdmin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_last_super_admin_user_cannot_be_deleted(): void
    {
        $admin = $this->createSuperAdmin();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_last_super_admin_cannot_demote_self(): void
    {
        $admin = $this->createSuperAdmin();

        $this->seedRbac();
        Role::create([
            'name' => 'staff',
            'guard_name' => AccessControl::GUARD,
        ])->syncPermissions([AccessControl::DASHBOARD_VIEW]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => '',
            'password_confirmation' => '',
            'role' => 'staff',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertTrue($admin->fresh()->hasRole(AccessControl::SUPER_ADMIN_ROLE));
    }

    public function test_users_can_be_sorted_by_email(): void
    {
        $admin = $this->createSuperAdmin();

        User::factory()->create([
            'name' => 'Zulu User',
            'email' => 'zulu@example.com',
        ]);

        User::factory()->create([
            'name' => 'Alpha User',
            'email' => 'alpha@example.com',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.index', [
            'sort' => 'email',
            'direction' => 'asc',
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['alpha@example.com', 'zulu@example.com']);
    }
}
