<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TenantProvisioningTest extends TestCase
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

    public function test_central_home_page_is_available_on_single_domain(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_creating_a_tenant_provisions_a_separate_database(): void
    {
        $tenant = Tenant::create(['id' => 'acme']);

        $databaseName = $tenant->database()->getName();
        $this->assertSame('tenantacme', $databaseName);
        $this->assertFileExists(database_path($databaseName));

        tenancy()->initialize($tenant);

        $this->assertSame($databaseName, basename(DB::connection()->getDatabaseName()));
        $this->assertTrue(DB::getSchemaBuilder()->hasTable('users'));

        tenancy()->end();
    }

    public function test_tenant_app_uses_session_selected_database(): void
    {
        Tenant::create(['id' => 'demo']);

        $this->from('/')
            ->post('/tenancy/enter/demo')
            ->assertRedirect('/app');

        $this->get('/app')
            ->assertOk()
            ->assertJsonPath('tenant_id', 'demo')
            ->assertJsonPath('database', database_path('tenantdemo'));
    }

    public function test_tenant_app_redirects_without_session(): void
    {
        $this->get('/app')->assertRedirect('/');
    }
}
