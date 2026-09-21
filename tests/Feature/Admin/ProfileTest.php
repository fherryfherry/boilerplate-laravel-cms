<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\InteractsWithAdminRbac;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use InteractsWithAdminRbac;
    use RefreshDatabase;

    public function test_super_admin_can_update_profile_and_password(): void
    {
        $admin = $this->createSuperAdmin();

        $response = $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Updated Admin',
            'email' => 'updated-admin@example.com',
            'current_password' => 'password',
            'new_password' => 'new-secret',
            'new_password_confirmation' => 'new-secret',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $this->assertSame('Updated Admin', $admin->fresh()->name);
        $this->assertSame('updated-admin@example.com', $admin->fresh()->email);
        $this->assertTrue(Hash::check('new-secret', $admin->fresh()->password));
    }
}
