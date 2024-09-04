<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'skipToCscrfRoutes' => '*'
        //'skipToCscrfRoutes' => 'admin/bookings/getAllActiveReservations',
    ];

    protected function addCookieToResponse($request, $response)
    {
        /*$config = config('session');

        $response->headers->setCookie(
            new Cookie(
                'XSRF-TOKEN', $request->session()->token(), Carbon::now()->getTimestamp() + 60 * $config['lifetime'],
                $config['path'], $config['domain'], $config['secure'], true
            )
        );*/

        return $response;
    }
}
