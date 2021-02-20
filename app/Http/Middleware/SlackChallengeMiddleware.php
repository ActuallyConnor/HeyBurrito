<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SlackChallengeMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle( Request $request, Closure $next ) {
        if ( $request->json()->has( 'challenge' ) ) {
            return response( [ 'challenge' => $request->json()->get( 'challenge' ) ] );
        }

        return $next( $request );
    }
}
