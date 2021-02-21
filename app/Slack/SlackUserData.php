<?php

namespace App\Slack;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

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

        return self::validateSlackData( self::getSlackMemberData( $username, $slack_users ) );
    }

    /**
     * Get list of Slack users from users.list endpoint
     * @return false|mixed
     */
    private static function getListOfSlackUsers() {
        $base_uri = env( 'SLACK_TESTING_URL', 'https://slack.com/api/' );
        $endpoint = 'users.list';
        $content_type = 'application/x-www-form-urlencoded';
        $token = env( 'BOT_OAUTH_TOKEN' );

        $response = Http::withHeaders( [
            'Content-Type' => $content_type,
            'Authorization' => 'Bearer ' . $token
        ] )->get( $base_uri . $endpoint );

        if ( $response->status() != 200 ) {
            Log::error( $response->body() );
            return false;
        }

        if ( $response->status() != 200 ) {
            Log::error( print_r( [
                'message' => 'Request did not return ok response',
                'response_code' => $response->status()
            ], true ) );
            return false;
        }

        $json = $response->getBody()->getContents();
        return json_decode( $json );
    }

    /**
     * @param $username
     * @param $slackUsers
     * @return object|false
     */
    private static function getSlackMemberData( $username, $slackUsers ) {
        if ( !isset( $slackUsers->members ) ) {
            return false;
        }
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
     * Validate the data coming from Slack is correct
     *
     * @param $member
     * @return object|false
     */
    private static function validateSlackData( $member ) {
        $validator = Validator::make( (array)$member, [
            'real_name' => [
                'bail',
                'required',
                'string',
                'max:255'
            ],
            'profile' => [
                'bail',
                'required',
                function( $attr, $value, $fail ) {
                    if ( !is_object( $value ) && !isset( $value->display_name ) ) {
                        $fail( 'The ' . $attr . ' is required to have a display_name from Slack' );
                    }
                }
            ],
            'id' => [
                'bail',
                'required',
                'string',
                'max:255'
            ]
        ] );

        if ( $validator->fails() ) {
            return false;
        }

        return $member;
    }
}
