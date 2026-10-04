<?php

namespace Tests\Feature;

use App\Filament\Auth\Login;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
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

    public function test_app_login_screen_can_be_rendered(): void
    {
        $this->get('/app/login')->assertOk();
    }

    public function test_authenticated_tenant_user_can_open_app_panel(): void
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

        Livewire::test(Login::class)
            ->fillForm([
                'tenant' => 'demo',
                'email' => 'demo@example.com',
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertRedirect('/app');

        $this->actingAs($user)
            ->withSession(['tenant_id' => 'demo'])
            ->get('/app')
            ->assertOk();
    }

    public function test_news_categories_resource_is_available(): void
    {
        $tenant = Tenant::create([
            'id' => 'demo',
            'name' => 'Demo Co',
        ]);

        tenancy()->initialize($tenant);
        $user = User::factory()->create();
        tenancy()->end();

        $this->actingAs($user)
            ->withSession(['tenant_id' => 'demo'])
            ->get('/app/news-categories')
            ->assertOk();
    }
}
