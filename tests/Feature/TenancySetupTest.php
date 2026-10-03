<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TenancySetupTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        tenancy()->end();

        foreach (File::glob(database_path('tenant*')) as $database) {
            File::delete($database);
        }

        parent::tearDown();
    }

    public function test_central_home_is_available_on_central_domain(): void
    {
        $this->get('http://localhost/')
            ->assertOk();
    }

    public function test_creating_a_tenant_provisions_a_separate_database(): void
    {
        $tenant = Tenant::create([
            'id' => 'news-a',
            'name' => 'Haber A',
        ]);

        $tenant->domains()->create([
            'domain' => 'news-a.localhost',
        ]);

        $this->assertDatabaseHas('tenants', [
            'id' => 'news-a',
            'name' => 'Haber A',
        ]);

        $this->assertDatabaseHas('domains', [
            'domain' => 'news-a.localhost',
            'tenant_id' => 'news-a',
        ]);

        $this->assertFileExists(database_path('tenantnews-a'));

        tenancy()->initialize($tenant);

        $this->assertSame(database_path('tenantnews-a'), DB::connection()->getDatabaseName());
        $this->assertTrue(Schema::hasTable('users'));

        User::factory()->create([
            'email' => 'editor@news-a.test',
        ]);

        $this->assertSame(1, User::query()->count());

        tenancy()->end();

        $this->assertSame(0, User::query()->count());
    }

    public function test_tenant_home_identifies_the_current_tenant(): void
    {
        $tenant = Tenant::create([
            'id' => 'news-b',
            'name' => 'Haber B',
        ]);

        $tenant->domains()->create([
            'domain' => 'news-b.localhost',
        ]);

        $this->get('http://news-b.localhost/')
            ->assertOk()
            ->assertSee('news-b');
    }
}
