<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogVisitor
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);


        /*
        |--------------------------------------------------------------------------
        | JANGAN CATAT HALAMAN VISITOR LOG
        |--------------------------------------------------------------------------
        |
        | Supaya saat Admin membuka /visitor-logs,
        | request tersebut tidak ikut menambah statistik visitor.
        |
        */

        if (
            $request->routeIs(
                'visitor-logs.*'
            )
        ) {
            return $response;
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN VISITOR
        |--------------------------------------------------------------------------
        */

        try {

            $userAgent =
                (string) $request
                    ->userAgent();


            VisitorLog::create([

                'user_id' =>
                    auth()->id(),

                'ip_address' =>
                    $request->ip(),

                'method' =>
                    $request->method(),

                'url' =>
                    $request->fullUrl(),

                'route_name' =>
                    $request
                        ->route()
                        ?->getName(),

                'referer' =>
                    $request
                        ->headers
                        ->get(
                            'referer'
                        ),

                'user_agent' =>
                    $userAgent,

                'browser' =>
                    $this->detectBrowser(
                        $userAgent
                    ),

                'device' =>
                    $this->detectDevice(
                        $userAgent
                    ),

                'platform' =>
                    $this->detectPlatform(
                        $userAgent
                    ),

                'visited_at' =>
                    now(),
            ]);

        } catch (\Throwable $e) {

            report($e);
        }


        return $response;
    }


    /*
    |--------------------------------------------------------------------------
    | DETECT BROWSER
    |--------------------------------------------------------------------------
    */

    private function detectBrowser(
        string $userAgent
    ): string {

        if (
            str_contains(
                $userAgent,
                'Edg/'
            )
        ) {
            return 'Microsoft Edge';
        }


        if (
            str_contains(
                $userAgent,
                'OPR/'
            )
            ||
            str_contains(
                $userAgent,
                'Opera'
            )
        ) {
            return 'Opera';
        }


        if (
            str_contains(
                $userAgent,
                'Chrome/'
            )
        ) {
            return 'Google Chrome';
        }


        if (
            str_contains(
                $userAgent,
                'Firefox/'
            )
        ) {
            return 'Mozilla Firefox';
        }


        if (
            str_contains(
                $userAgent,
                'Safari/'
            )
            &&
            !str_contains(
                $userAgent,
                'Chrome/'
            )
            &&
            !str_contains(
                $userAgent,
                'Edg/'
            )
        ) {
            return 'Safari';
        }


        return 'Unknown';
    }


    /*
    |--------------------------------------------------------------------------
    | DETECT DEVICE
    |--------------------------------------------------------------------------
    */

    private function detectDevice(
        string $userAgent
    ): string {

        if (
            preg_match(
                '/ipad|tablet/i',
                $userAgent
            )
        ) {
            return 'Tablet';
        }


        if (
            preg_match(
                '/mobile|android|iphone/i',
                $userAgent
            )
        ) {
            return 'Mobile';
        }


        return 'Desktop';
    }


    /*
    |--------------------------------------------------------------------------
    | DETECT PLATFORM
    |--------------------------------------------------------------------------
    */

    private function detectPlatform(
        string $userAgent
    ): string {

        if (
            str_contains(
                $userAgent,
                'Windows'
            )
        ) {
            return 'Windows';
        }


        if (
            str_contains(
                $userAgent,
                'Android'
            )
        ) {
            return 'Android';
        }


        if (
            str_contains(
                $userAgent,
                'iPhone'
            )
            ||
            str_contains(
                $userAgent,
                'iPad'
            )
        ) {
            return 'iOS';
        }


        if (
            str_contains(
                $userAgent,
                'Macintosh'
            )
        ) {
            return 'macOS';
        }


        if (
            str_contains(
                $userAgent,
                'Linux'
            )
        ) {
            return 'Linux';
        }


        return 'Unknown';
    }
}