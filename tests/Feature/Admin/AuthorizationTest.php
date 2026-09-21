<?php

namespace Tests\Feature\Admin;

use App\Support\AccessControl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\Concerns\InteractsWithAdminRbac;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use InteractsWithAdminRbac;
    use RefreshDatabase;

    public function test_user_without_required_permissions_receives_403(): void
    {
        $user = $this->createUserWithPermissions([]);

        $this->actingAs($user)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.roles.index'))->assertForbidden();
    }

    public function test_sidebar_only_shows_authorized_menu_items(): void
    {
        $user = $this->createUserWithPermissions([AccessControl::USER_VIEW]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee(route('admin.users.index'));
        $response->assertDontSee(route('admin.roles.index'));
    }

    public function test_sidebar_layout_is_used_by_default(): void
    {
        $user = $this->createUserWithPermissions([]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('data-navigation-layout="sidebar"', false);
    }

    public function test_topbar_layout_can_be_enabled_via_config(): void
    {
        Config::set('admin.navigation_layout', 'topbar');

        $user = $this->createUserWithPermissions([AccessControl::USER_VIEW]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('data-navigation-layout="topbar"', false);
        $response->assertSee(route('admin.users.index'));
    }
}
