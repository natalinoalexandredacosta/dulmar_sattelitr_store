<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IdleTimeout
{
    private const TIMEOUT_SECONDS = 600;

    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (Auth::check()) {
            $lastActivity = $request
                ->session()
                ->get('last_activity_time');

            if (
                $lastActivity &&
                (time() - $lastActivity) >= self::TIMEOUT_SECONDS
            ) {
                Auth::logout();

                $request
                    ->session()
                    ->invalidate();

                $request
                    ->session()
                    ->regenerateToken();

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' =>
                            'Sesi Anda berakhir karena tidak ada aktivitas selama 10 menit. Silakan login kembali.',
                    ]);
            }

            $request
                ->session()
                ->put(
                    'last_activity_time',
                    time()
                );
        }

        return $next($request);
    }
}