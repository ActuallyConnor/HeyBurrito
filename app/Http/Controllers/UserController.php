<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
	public function addUser( Request $request ) {
		$data = $request->json();
		
		if ( empty( $data ) ) {
			return $this->logAndSendErrorResponse( '' . 'No JSON body in request' );
		}
		if ( !$data->has( 'name' ) ) {
			return $this->logAndSendErrorResponse( 'name' );
		}
		if ( !$data->has( 'email' ) ) {
			return $this->logAndSendErrorResponse( 'email' );
		}
		if ( !$data->has( 'username' ) ) {
			return $this->logAndSendErrorResponse( 'username' );
		}
		
		$name = $data->get( 'name' );
		$email = $data->get( 'email' );
		$username = $data->get( 'username' );
		
		$user = new User();
		
		$user->name = $name;
		$user->email = $email;
		$user->username = $username;
		
		$userAdded = $user->save();
		
		if ( !$userAdded ) {
			return $this->logAndSendErrorResponse( '', 'User unable to be added to database', 500 );
		}
		
		Log::info( 'User successfully added' );
		
		$userValidationSent = $this->sendValidationEmailToUser( $name, $email, $username );
		
		if ( !$userValidationSent ) {
			return $this->logAndSendErrorResponse( '', 'Unable to send validation email to user, user still added to database' );
		}
		
		Log::info( 'Validation email successfully sent' );
		
		return response( 'User successfully added and validation email successfully sent' );
	}
	
	/**
	 * Handles logging and response for bad request
	 * @param string $dataAttrKey
	 * @param string $customMessage
	 * @param int $statusCode
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	private function logAndSendErrorResponse( $dataAttrKey = '', $customMessage = '', $statusCode = 400 ) {
		Log::error( print_r( [
			'400',
			'Error in UserController::addUser()',
			!empty( $customMessage ) ? : sprintf( 'No %s in JSON body', $dataAttrKey )
		], true ) );
		return response( !empty( $customMessage ) ? : sprintf( 'No %s in JSON body', $dataAttrKey ), $statusCode );
	}
	
	/**
	 * Send validation email to user
	 * @param $name
	 * @param $email
	 * @param $username
	 * @return bool
	 */
	private function sendValidationEmailToUser( $name, $email, $username ) {
		return true;
	}
}
