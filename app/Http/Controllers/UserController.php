<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Throwable;


class UserController extends Controller {
	
	/**
	 * Adds the user to the database
	 * @param Request $request
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function addUser( Request $request ) {
		$data = $request->json();
		
		if ( empty( $data ) ) {
			return $this->logAndSendErrorResponse( '' . 'No JSON body in request' );
		}
		if ( !$data->has( 'username' ) ) {
			return $this->logAndSendErrorResponse( 'username' );
		}
		
		/**
		 * TODO: Get user information using users:read slack API endpoint
		 * From there get the name, email, and user_id
		 * Usernames can change but user IDs cannot so that is more important than username
		 * Username will just help with caching results
		 */
		
		$name = $data->get( 'name' );
		$email = $data->get( 'email' );
		$username = $data->get( 'username' );
		$user_id = $data->get( 'user_id' );
		
		$user = new User();
		
		$user->name = $name;
		$user->email = $email;
		$user->username = $username;
		$user->user_id = $user_id;
		$user->active = true;
		
		$userAdded = $user->save();
		
		if ( !$userAdded ) {
			return $this->logAndSendErrorResponse( '', 'User unable to be added to database', 500 );
		}
		
		Log::info( 'User successfully added' );
		return response( 'User successfully added' );
	}
	
	public function removeUser( Request $request ) {
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
		if ( !$data->has( 'user_id' ) ) {
			return $this->logAndSendErrorResponse( 'user_id' );
		}
	}
	
	/**
	 * Use the users.list API endpoint to retrieve a list of all users and then search through them for the matching username
	 * @param $username
	 * @return false|\Psr\Http\Message\ResponseInterface
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function getUserInformationFromSlack( $username ) {
		$base_uri = 'https://slack.com/api/';
		$endpoint = 'users.list';
		$method = 'GET';
		$content_type = 'application/x-www-form-urlencoded';
		$token = env( 'BOT_OAUTH_TOKEN' );
		
		$client = new Client( [
			'base_uri' => $base_uri
		] );
		
		try {
			$response = $client->request( $method, $endpoint, [
				'headers' => [
					'Content-Type' => $content_type,
					'auth' => 'Bearer ' . $token
				]
			] );
		} catch ( Throwable $e ) {
			Log::error( $e );
			return false;
		}
		
		return $response;
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
}
