<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthenticationTest extends TestCase
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

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_users_can_register_and_reach_dashboard(): void
    {
        $response = $this->post('/register', [
            'company' => 'Acme Inc',
            'tenant' => 'acme',
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertSame('acme', session('tenant_id'));
        $this->assertDatabaseHas('tenants', ['id' => 'acme'], config('tenancy.database.central_connection'));

        $this->get('/app')
            ->assertOk()
            ->assertSee('Ada Lovelace')
            ->assertSee('Acme Inc');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_users_can_authenticate_with_tenant_credentials(): void
    {
        $tenant = Tenant::create([
            'id' => 'demo',
            'name' => 'Demo Co',
        ]);

        tenancy()->initialize($tenant);

        User::factory()->create([
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        tenancy()->end();

        $response = $this->post('/login', [
            'tenant' => 'demo',
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertSame('demo', session('tenant_id'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $tenant = Tenant::create(['id' => 'demo', 'name' => 'Demo Co']);

        tenancy()->initialize($tenant);
        User::factory()->create([
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);
        tenancy()->end();

        $this->post('/login', [
            'tenant' => 'demo',
            'email' => 'demo@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $tenant = Tenant::create(['id' => 'demo', 'name' => 'Demo Co']);

        tenancy()->initialize($tenant);
        $user = User::factory()->create();
        tenancy()->end();

        $this->withSession(['tenant_id' => 'demo'])
            ->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertNull(session('tenant_id'));
    }
}
