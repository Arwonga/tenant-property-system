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
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. THIS IS THE MISSING KEY! It checks the password and logs you in.
        $request->authenticate();

        // 2. Secures the session
        $request->session()->regenerate();

        // 3. The Traffic Cop redirect logic
        $url = '';
        if ($request->user()->role === 'admin') {
            $url = '/admin/dashboard';
        } elseif ($request->user()->role === 'caretaker') {
            $url = '/caretaker/dashboard'; 
        } else {
            $url = '/tenant/dashboard';
        }

        return redirect()->intended($url);
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
