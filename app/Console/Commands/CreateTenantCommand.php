<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Throwable;

class CreateTenantCommand extends Command
{
    protected $signature = 'tenants:create
                            {id? : Tenant id / slug stored in the central DB (UUID if omitted)}
                            {--name= : Optional display name stored on the tenant}';

    protected $description = 'Create a tenant and provision its dedicated database (session-based single-domain tenancy)';

    public function handle(): int
    {
        $attributes = [];

        $id = $this->argument('id');

        if (is_string($id) && $id !== '') {
            if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $id)) {
                $this->error('Tenant id must be a URL-safe slug (lowercase letters, numbers, hyphens).');

                return self::FAILURE;
            }

            $attributes['id'] = $id;
        }

        if (is_string($this->option('name')) && $this->option('name') !== '') {
            $attributes['name'] = $this->option('name');
        }

        try {
            $tenant = Tenant::create($attributes);
        } catch (Throwable $exception) {
            $this->error('Failed to create tenant: '.$exception->getMessage());
            $this->warn('MySQL users need CREATE DATABASE privilege to auto-provision tenant databases.');

            return self::FAILURE;
        }

        $tenantKey = (string) $tenant->getTenantKey();

        $this->info('Tenant created.');
        $this->line('  id:       '.$tenantKey);
        $this->line('  database: '.$tenant->database()->getName());
        $this->comment('Select tenant into session (temporary until auth exists):');
        $this->line('  POST /tenancy/enter/'.$tenantKey);
        $this->line('  then open /app');

        return self::SUCCESS;
    }
}
