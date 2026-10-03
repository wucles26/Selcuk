<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Throwable;

class CreateTenantCommand extends Command
{
    protected $signature = 'tenants:create
                            {id? : Optional tenant id (UUID generated if omitted)}
                            {--domain= : Domain that identifies this tenant (required)}
                            {--name= : Optional display name stored on the tenant}';

    protected $description = 'Create a tenant, provision its database, and attach a domain';

    public function handle(): int
    {
        $domain = $this->option('domain');

        if (! is_string($domain) || $domain === '') {
            $this->error('The --domain option is required.');

            return self::FAILURE;
        }

        $attributes = [];

        if (is_string($this->argument('id')) && $this->argument('id') !== '') {
            $attributes['id'] = $this->argument('id');
        }

        if (is_string($this->option('name')) && $this->option('name') !== '') {
            $attributes['name'] = $this->option('name');
        }

        try {
            $tenant = Tenant::create($attributes);
            $tenant->domains()->create(['domain' => $domain]);
        } catch (Throwable $exception) {
            $this->error('Failed to create tenant: '.$exception->getMessage());
            $this->warn('MySQL users need CREATE DATABASE privilege to auto-provision tenant databases.');

            return self::FAILURE;
        }

        $this->info('Tenant created.');
        $this->line('  id:       '.$tenant->getTenantKey());
        $this->line('  domain:   '.$domain);
        $this->line('  database: '.$tenant->database()->getName());

        return self::SUCCESS;
    }
}
