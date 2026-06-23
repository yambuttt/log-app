<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($user->role, $roles, true)) {
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'warehouse' => redirect()->route('warehouse.dashboard'),
                'driver' => redirect()->route('driver.dashboard'),
                default => redirect()->route('login'),
            };
        }

        return $next($request);
    }
}