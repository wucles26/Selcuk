<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FilamentAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        if (tenancy()->initialized) {
            tenancy()->end();
        }

        DB::purge('tenant');

        foreach (glob(database_path('tenant*')) ?: [] as $database) {
            @unlink($database);
        }

        parent::tearDown();
    }

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_authenticated_tenant_user_can_open_admin_panel(): void
    {
        $tenant = Tenant::create([
            'id' => 'demo',
            'name' => 'Demo Co',
        ]);

        tenancy()->initialize($tenant);

        $user = User::factory()->create([
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        tenancy()->end();

        $this->post('/login', [
            'tenant' => 'demo',
            'email' => 'demo@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->actingAs($user)
            ->withSession(['tenant_id' => 'demo'])
            ->get('/admin')
            ->assertOk();
    }
}
