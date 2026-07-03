<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Approval hanya untuk client
        |--------------------------------------------------------------------------
        | Admin, pegawai, dan pimpinan tidak perlu approval.
        | Client baru wajib disetujui admin sebelum bisa masuk dashboard.
        */
        if ($user->role === 'client' && !$user->is_approved) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun Anda sedang menunggu persetujuan Administrator.',
                ])
                ->onlyInput('email');
        }

        return match ($user->role) {
            'admin' => redirect('/admin/dashboard'),
            'pegawai' => redirect('/pegawai/dashboard'),
            'pimpinan' => redirect('/pimpinan/dashboard'),
            default => redirect('/client/dashboard'),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}