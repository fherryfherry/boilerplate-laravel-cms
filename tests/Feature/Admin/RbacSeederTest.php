<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Support\AccessControl;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_seed_user_receives_super_admin_role_and_full_access(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->assertTrue($admin->hasRole(AccessControl::SUPER_ADMIN_ROLE));
        $this->assertTrue($admin->can(AccessControl::ROLE_DELETE));

        $this->actingAs($admin)
            ->get(route('admin.roles.index'))
            ->assertOk();
    }
}
