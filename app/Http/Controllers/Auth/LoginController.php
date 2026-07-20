<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Handle authentication attempts.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Check if the account is currently locked
            if ($user->locked_until && now()->lt($user->locked_until)) {
                $secondsLeft = now()->diffInSeconds($user->locked_until);
                $minutesLeft = ceil($secondsLeft / 60);

                return back()->withErrors([
                    'email' => "Akun Anda sedang ditangguhkan. Silakan coba lagi dalam {$minutesLeft} menit."
                ])->withInput($request->only('email'));
            }
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Reset attempts on success
            if ($user) {
                $user->update([
                    'login_attempts' => 0,
                    'locked_until' => null,
                ]);
            }

            // Clear the login rate limiter key
            $key = 'login_' . sha1($request->ip() . '|' . $credentials['email']);
            RateLimiter::clear($key);

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // Increment attempts on failure
        if ($user) {
            $user->increment('login_attempts');
            if ($user->login_attempts >= 5) {
                $user->update([
                    'locked_until' => now()->addMinutes(15)
                ]);

                return back()->withErrors([
                    'email' => 'Terlalu banyak percobaan login salah. Akun Anda dikunci selama 15 menit.'
                ])->withInput($request->only('email'));
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
