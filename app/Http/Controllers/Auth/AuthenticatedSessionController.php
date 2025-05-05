<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Notifications\LoginNotification;
use App\Providers\RouteServiceProvider;
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
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Send notification to all admin users
        $admins = User::where('role', 'admin')
                        ->where('id', '!=', $request->user()->id)
                        ->get();

        // Send notification only once per admin
        foreach ($admins as $admin) {
            // Check if a similar notification was sent in the last minute to prevent duplicates
            $recentNotification = $admin->notifications()
                ->where('type', LoginNotification::class)
                ->where('created_at', '>=', now()->subMinute())
                ->first();

            if (!$recentNotification) {
                $admin->notify(new LoginNotification($request->user()));
            }
        }

        // foreach ($admins as $admin) {
        //     $admin->notify(new LoginNotification($request->user()));
        // }

        $notification = array(
            'message' => 'Login Successfully',
            'alert-type' => 'success'
        );

        $url = '';
        if ($request->user()->role === 'admin') {
            $url = 'admin/dashboard';
        } elseif ($request->user()->role === 'user') {
            $url = 'user/dashboard';
        }
        return redirect()->intended($url)->with($notification);
        // return redirect()->intended(RouteServiceProvider::HOME);
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
