<?php

namespace App\Http\Middleware;

use App\Enums\StatusCode;
use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailVerified
{
    use HttpResponses;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!$request->user() || !$request->user()->email_verified_at) {
            return $this->failed(null, StatusCode::Forbidden->value, 'You must verify your email to continue');
        }
        return $next($request);
    }
}
