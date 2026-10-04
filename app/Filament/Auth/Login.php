<?php

namespace App\Filament\Auth;

use App\Models\Tenant;
use App\Support\TenantSession;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getTenantFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    public function authenticate(): ?LoginResponse
    {
        $tenantCode = strtolower(trim((string) ($this->data['tenant'] ?? '')));

        if ($tenantCode !== '') {
            $tenant = Tenant::find($tenantCode);

            if (! $tenant) {
                try {
                    $this->rateLimit(5);
                } catch (TooManyRequestsException $exception) {
                    $this->getRateLimitedNotification($exception)?->send();

                    return null;
                }

                throw ValidationException::withMessages([
                    'data.tenant' => 'Organizasyon bulunamadı.',
                ]);
            }

            if (tenancy()->initialized) {
                tenancy()->end();
            }

            tenancy()->initialize($tenant);
        }

        try {
            $response = parent::authenticate();
        } catch (ValidationException $exception) {
            if (tenancy()->initialized) {
                tenancy()->end();
            }

            TenantSession::forget();

            throw $exception;
        }

        if ($response instanceof LoginResponse && tenancy()->initialized) {
            TenantSession::remember((string) tenancy()->tenant->getTenantKey());
        }

        return $response;
    }

    protected function getTenantFormComponent(): Component
    {
        return TextInput::make('tenant')
            ->label('Organizasyon kodu')
            ->placeholder('ornek-firma')
            ->required()
            ->maxLength(50)
            ->alphaDash()
            ->autocomplete('organization')
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->autofocus(false);
    }
}
