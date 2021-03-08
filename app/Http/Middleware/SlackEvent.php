<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SlackEvent {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle( Request $request, Closure $next ) {
        // Slack verification token is not passed in request
        if ( empty( $request->all()[ 'token' ] ) ) {
            return redirect( '/' );
        }

        // Incorrect Slack verification token is passed in request
        if ( $request->all()[ 'token' ] !== env( 'VERIFICATION_TOKEN' ) ) {
            return redirect( '/' );
        }

        if ( $request->json()->has( 'challenge' ) ) {
            return response( [ 'challenge' => $request->json()->get( 'challenge' ) ], 200 );
        }

        return $next( $request );
    }
}
