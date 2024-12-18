<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[]  $roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (Auth::check()) {
            $expireTime = Carbon::now()->addMinutes(30);
            Cache::put('user-is-online' . Auth::user()->id, true, $expireTime);
            User::where('id', Auth::user()->id)->update(['last_seen' => Carbon::now()]);
        }

        // Get the role of the authenticated user
        $userRole = $request->user()->role;

        // Check if the user's role is in the list of allowed roles
        if (in_array($userRole, $roles)) {
            // If the user has the right role, let the request go through
            return $next($request);
        } elseif ($userRole === 'user') {
            // If the user is a regular user, redirect them to their dashboard
            return redirect('dashboard');
        } elseif ($userRole === 'admin') {
            // If the user is an admin, redirect them to their dashboard
            return redirect('/admin/dashboard');
        }

        // If the user doesn't have the right role, throw a 403 error
        return abort(403);
    }
}
