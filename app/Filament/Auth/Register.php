<?php

namespace App\Filament\Auth;

use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantSession;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;
use Throwable;

class Register extends BaseRegister
{
    protected ?bool $hasDatabaseTransactions = false;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getCompanyFormComponent(),
                $this->getTenantFormComponent(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRegistration(#[SensitiveParameter] array $data): Model
    {
        try {
            $tenant = Tenant::create([
                'id' => strtolower((string) $data['tenant']),
                'name' => (string) $data['company'],
            ]);
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'data.tenant' => 'Organizasyon oluşturulamadı. Veritabanı yetkilerini kontrol edin.',
            ]);
        }

        if (tenancy()->initialized) {
            tenancy()->end();
        }

        tenancy()->initialize($tenant);
        TenantSession::remember((string) $tenant->getTenantKey());

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }

    protected function getCompanyFormComponent(): Component
    {
        return TextInput::make('company')
            ->label('Şirket / organizasyon adı')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getTenantFormComponent(): Component
    {
        return TextInput::make('tenant')
            ->label('Organizasyon kodu')
            ->placeholder('ornek-firma')
            ->required()
            ->maxLength(50)
            ->alphaDash()
            ->rule(Rule::unique('tenants', 'id'))
            ->validationMessages([
                'unique' => 'Bu organizasyon kodu zaten kullanılıyor.',
                'alpha_dash' => 'Organizasyon kodu yalnızca harf, rakam, tire ve alt çizgi içerebilir.',
            ])
            ->dehydrateStateUsing(fn (string $state): string => strtolower($state));
    }

    protected function getNameFormComponent(): Component
    {
        return parent::getNameFormComponent()
            ->autofocus(false);
    }

    protected function getEmailFormComponent(): Component
    {
        // Uniqueness is per-tenant DB; central connection has no tenant users yet.
        return TextInput::make('email')
            ->label(__('filament-panels::auth/pages/register.form.email.label'))
            ->email()
            ->required()
            ->maxLength(255);
    }
}
