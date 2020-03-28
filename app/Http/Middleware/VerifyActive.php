<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;

class VerifyActive
{

    use ApiResponse;


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( auth()->user()->verified_email ){
            return $next($request);
        }

        return $this->specialResponse([
            'access_token'  => null,
            'token_type'    => null,
            'expires_at'    => null,
            'status'        => 'forbidden',
            'code'          => 403,
            'message'       => 'Your account is not active.',
            'data'          => []
        ],403);

    }
}
