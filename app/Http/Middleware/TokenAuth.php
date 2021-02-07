<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenAuth {
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle( Request $request, Closure $next ) {
		if ( !$request->hasHeader( 'hey-burrito-token' ) ) {
			return redirect('/');
		}
		if ( $request->header( 'hey-burrito-token' ) !== env( 'HEY_BURRITO_AUTH_TOKEN' ) ) {
			return redirect( '/' );
		}
		
		return $next( $request );
	}
}
