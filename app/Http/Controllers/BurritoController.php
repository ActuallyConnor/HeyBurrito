<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BurritoController extends Controller {
	public function giveBurrito( Request $request ) {
	
	}
	
	public function slackChallenge( Request $request ) {
		if ( !empty( $request->json() ) ) {
			$json = $request->json();
			
			if ($json->has('challenge')) {
				$challenge = $json->get('challenge');
			} elseif ($json->has('body')) {
				$challenge = $json->get( 'body' )['challenge'];
			} else {
				$challenge = '';
			}
			
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
