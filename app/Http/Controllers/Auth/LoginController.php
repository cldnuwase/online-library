<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';
    protected $adminRedirectTo = '/admin/dashboard';

    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->is_admin ? 
                redirect($this->adminRedirectTo) : 
                redirect($this->redirectTo);
        }
        return view('auth.login');
    }

    public function showAdminLoginForm()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect($this->adminRedirectTo);
        }
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->is_admin) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')
                    ->withError('Please use the admin login page.');
            }

            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo);
        }

        return back()->withErrors([
            'email' => 'incorrect email or password please try again with correct one!.',
        ])->onlyInput('email');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->filled('remember_admin');
        
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            if (!$user->is_admin) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->withError('This login is for administrators only.');
            }

            $request->session()->regenerate();
            return redirect()->intended($this->adminRedirectTo);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
