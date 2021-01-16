<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BurritoController extends Controller {
	public function giveBurrito(Request $request) {
		$this->slackChallenge($request);
	}
	
	/**
	 * @param Request $request
	 * @return false|Response
	 */
	private function slackChallenge(Request $request) {
		if ($request->has('challenge')) {
			$requestJson = $request->input('challenge');
			$requestArr = json_decode($requestJson, true);
			
			return response($requestArr['challenge'])
				->header('Content-type', 'text/plain' );
		}
		return false;
	}
}
