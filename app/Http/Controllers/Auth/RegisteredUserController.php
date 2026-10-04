<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company' => ['required', 'string', 'max:255'],
            'tenant' => ['required', 'string', 'max:50', 'alpha_dash:ascii', 'unique:tenants,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'tenant.unique' => 'Bu organizasyon kodu zaten kullanılıyor.',
            'tenant.alpha_dash' => 'Organizasyon kodu yalnızca harf, rakam, tire ve alt çizgi içerebilir.',
        ]);

        try {
            $tenant = Tenant::create([
                'id' => strtolower($validated['tenant']),
                'name' => $validated['company'],
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['tenant' => 'Organizasyon oluşturulamadı. Veritabanı yetkilerini kontrol edin.']);
        }

        tenancy()->initialize($tenant);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        TenantSession::remember((string) $tenant->getTenantKey());
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
