<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
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
        $this->get('/')->assertOk();
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

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/app')->assertRedirect(route('login'));
    }
}
