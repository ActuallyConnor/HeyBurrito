<?php


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SlackUserData {
	
	/**
	 * Get the specific Slack member data based on the provided username, or return false if user is deactivated or
	 * does not exist
	 * @param $username
	 * @return false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public static function getUserInformationFromSlack( $username ) {
		$slack_users = self::getListOfSlackUsers();
		
		if ( !$slack_users ) {
			return false;
		}
		
		return self::getSlackMemberData( $username, $slack_users );
	}
	
	/**
	 * Get list of Slack users from users.list endpoint
	 * @return false|mixed
	 */
	private static function getListOfSlackUsers() {
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
	private static function getSlackMemberData( $username, $slackUsers ) {
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
}
