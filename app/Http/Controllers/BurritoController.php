<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BurritoController extends Controller {
	public function giveBurrito( Request $request ) {
		return $this->slackChallenge( $request );
	}
	
	private function slackChallenge( Request $request ) {
		if ( !empty( $request->json() ) ) {
			$json = $request->json();
			$body = $json->get( 'body' );
			$challenge = $body[ 'challenge' ];
			return response()
				->json( [
					'challenge' => $challenge
				] )
				->header( 'Access-Control-Allow-Origin', '*' );
		}
		return response( 'No payload in request' )
			->header( 'Content-Type', 'text/plain' );
	}
}
