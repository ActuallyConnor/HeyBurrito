<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BurritoController extends Controller {
	public function giveBurrito( Request $request ) {
		$this->slackChallenge( $request );
	}
	
	private function slackChallenge( Request $request ) {
		if ( !empty( $request->json() ) ) {
			$body = $request->json();
			$challenge = $body->get( 'challenge' );
			return response()
				->json([
					'challenge' => $challenge
				]);
		}
		return response('No payload in request')
			->header('Content-Type', 'text/plain');
	}
}
