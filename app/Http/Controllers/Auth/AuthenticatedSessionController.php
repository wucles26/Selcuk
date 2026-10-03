<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'tenant' => ['required', 'string', 'max:50', 'alpha_dash:ascii'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $tenant = Tenant::find(strtolower($credentials['tenant']));

        if (! $tenant) {
            throw ValidationException::withMessages([
                'tenant' => 'Organizasyon bulunamadı.',
            ]);
        }

        tenancy()->initialize($tenant);

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            tenancy()->end();

            throw ValidationException::withMessages([
                'email' => 'E-posta veya şifre hatalı.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put('tenant_id', $tenant->getTenantKey());
        tenancy()->end();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (tenancy()->initialized) {
            tenancy()->end();
        }

        return redirect()->route('login');
    }
}
