<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Concerns\InteractsWithAdminRbac;

class AuthTest extends TestCase
{
    use InteractsWithAdminRbac;
    use RefreshDatabase;

    private const ADMIN_LOGIN_PATH = '/admin/login';
    private const ADMIN_LOGIN_SUBMIT_PATH = '/admin/login';

    public function test_admin_login_page_is_accessible_to_guests(): void
    {
        Config::set('admin.login_layout', 'panel');

        $response = $this->get(self::ADMIN_LOGIN_PATH);

        $response->assertOk();
        $response->assertSee('data-login-layout="panel"', false);
    }

    public function test_authenticated_user_is_redirected_away_from_admin_login_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(self::ADMIN_LOGIN_PATH);

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $this->seedRbac();

        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
        ]);
        $user->assignRole(\App\Support\AccessControl::SUPER_ADMIN_ROLE);

        $response = $this->post(self::ADMIN_LOGIN_SUBMIT_PATH, [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_cannot_login_with_invalid_credentials(): void
    {
        $this->seedRbac();

        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
        ])->assignRole(\App\Support\AccessControl::SUPER_ADMIN_ROLE);

        $response = $this->from(self::ADMIN_LOGIN_PATH)->post(self::ADMIN_LOGIN_SUBMIT_PATH, [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(self::ADMIN_LOGIN_PATH);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_split_login_layout_can_be_enabled_via_config(): void
    {
        Config::set('admin.login_layout', 'split');

        $response = $this->get(self::ADMIN_LOGIN_PATH);

        $response->assertOk();
        $response->assertSee('data-login-layout="split"', false);
        $response->assertSee('Admin access built for focused work.');
    }

    public function test_spotlight_login_layout_can_be_enabled_via_config(): void
    {
        Config::set('admin.login_layout', 'spotlight');

        $response = $this->get(self::ADMIN_LOGIN_PATH);

        $response->assertOk();
        $response->assertSee('data-login-layout="spotlight"', false);
        $response->assertSee('One secure entrance for every admin workflow.');
    }
}
