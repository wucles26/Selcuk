<?php

namespace Tests\Feature;

use App\Filament\Auth\Login;
use App\Filament\Auth\Register;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
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
        $this->get('/app/register')->assertOk();
        $this->get('/register')->assertRedirect('/app/register');
    }

    public function test_users_can_register_and_reach_dashboard(): void
    {
        Livewire::test(Register::class)
            ->fillForm([
                'company' => 'Acme Inc',
                'tenant' => 'acme',
                'name' => 'Ada Lovelace',
                'email' => 'ada@example.com',
                'password' => 'password',
                'passwordConfirmation' => 'password',
            ])
            ->call('register')
            ->assertHasNoFormErrors()
            ->assertRedirect('/app');

        $this->assertAuthenticated();
        $this->assertSame('acme', session('tenant_id'));
        $this->assertDatabaseHas('tenants', ['id' => 'acme'], config('tenancy.database.central_connection'));

        $this->get('/app')
            ->assertOk()
            ->assertSee('Ada Lovelace');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/app/login')->assertOk();
        $this->get('/login')->assertRedirect('/app/login');
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

        Livewire::test(Login::class)
            ->fillForm([
                'tenant' => 'demo',
                'email' => 'demo@example.com',
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect('/app');

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

        Livewire::test(Login::class)
            ->fillForm([
                'tenant' => 'demo',
                'email' => 'demo@example.com',
                'password' => 'wrong-password',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

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
            ->post('/app/logout')
            ->assertRedirect('/app/login');

        $this->assertGuest();
        $this->assertNull(session('tenant_id'));
    }

    public function test_home_page_shows_dashboard_link_for_authenticated_tenant_users(): void
    {
        $tenant = Tenant::create(['id' => 'demo', 'name' => 'Demo Co']);

        tenancy()->initialize($tenant);
        $user = User::factory()->create(['name' => 'Demo User']);
        tenancy()->end();

        $this->withSession(['tenant_id' => 'demo'])
            ->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertSee('Panele git')
            ->assertDontSee('Giriş yap');
    }

    public function test_guest_middleware_redirects_authenticated_tenant_users_from_login(): void
    {
        $tenant = Tenant::create(['id' => 'demo', 'name' => 'Demo Co']);

        tenancy()->initialize($tenant);
        $user = User::factory()->create();
        tenancy()->end();

        $this->withSession(['tenant_id' => 'demo'])
            ->actingAs($user)
            ->get('/login')
            ->assertRedirect('/app');
    }

    public function test_login_after_register_can_open_dashboard_without_redirect_loop(): void
    {
        Livewire::test(Register::class)
            ->fillForm([
                'company' => 'Loop Free Inc',
                'tenant' => 'loopfree',
                'name' => 'Loop Free',
                'email' => 'loopfree@example.com',
                'password' => 'password',
                'passwordConfirmation' => 'password',
            ])
            ->call('register')
            ->assertRedirect('/app');

        $this->get('/app')
            ->assertOk()
            ->assertSee('Loop Free');

        $this->get('/login')
            ->assertRedirect('/app');

        $this->get('/app')
            ->assertOk();
    }

    public function test_incomplete_auth_session_does_not_loop_between_login_and_app(): void
    {
        $tenant = Tenant::create(['id' => 'demo', 'name' => 'Demo Co']);

        tenancy()->initialize($tenant);
        $user = User::factory()->create();
        tenancy()->end();

        // Authenticated in the guard but missing tenant context should recover
        // to a safe page instead of bouncing /login ↔ /app.
        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect('/');

        $this->get('/app')
            ->assertRedirect('/app/login');
    }

    public function test_legacy_admin_urls_redirect_to_app_panel(): void
    {
        $this->get('/admin')->assertRedirect('/app');
        $this->get('/admin/login')->assertRedirect('/app/login');
    }
}
