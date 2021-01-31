<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
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
		
		$username = $data->get( 'username' );
		$user_info = $this->getUserInformationFromSlack( $username );
		
		if (!$user_info) {
			return $this->logAndSendErrorResponse( '', 'Unable to get userdata from slack', 500 );
		}
		
		$user = new User();

		$user->name = $user_info->real_name;
		$user->username = $user_info->profile->display_name;
		$user->user_id = $user_info->id;
		$user->active = true;
		$user->total_received = 0;
		$user->total_given = 0;
		$user->total_redeemable = 0;

		$userAdded = $user->save();

		if ( !$userAdded ) {
			return $this->logAndSendErrorResponse( '', 'User unable to be added to database', 500 );
		}

		Log::info( 'User successfully added' );
		return response( json_encode( $user_info ) );
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
	 * @return false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	private function getUserInformationFromSlack( $username ) {
		$slack_users = $this->getListOfSlackUsers();
		
		if ( !$slack_users ) {
			echo 'failure';
		}
		
		return $this->getSlackMemberData( $username, $slack_users );
	}
	
	/**
	 * @return false|mixed
	 */
	private function getListOfSlackUsers() {
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
					'Authorization' => 'Bearer ' . $token
				]
			] );
		} catch ( Throwable $e ) {
			Log::error( $e );
			return false;
		}
		
		if ( $response->getStatusCode() != 200 ) {
			Log::error( print_r( [
				'message' => 'Request did not return ok response',
				'response_code' => $response->getStatusCode()
			], true ) );
			return false;
		}
		
		$json = $response->getBody()->getContents();
		return json_decode( $json );
	}
	
	/**
	 * @param $username
	 * @param $slackUsers
	 * @return false
	 */
	private function getSlackMemberData( $username, $slackUsers ) {
		$members = $slackUsers->members;
		
		foreach ( $members as $member ) {
			
			// Fail up front
			if ( $member->deleted ) {
				continue;
			}
			if ( $username !== $member->profile->display_name ) {
				continue;
			}
			
			return $member;
		}
		
		return false;
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
