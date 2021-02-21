<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MockSlackAPI extends Controller {

    public function event( $eventType ) {
        switch ( $eventType ) {
            case 'app_mention':
                $specificEventData = $this->getAppMentionEventData();
                break;
            case 'message':
                $specificEventData = $this->getMessageEventData();
                break;
            default:
                $specificEventData = array();
                break;
        }
        $eventData = $this->getEventData( $specificEventData );


        $response = Http::post( env( 'APP_URL' ) . '/api/event', $eventData );

        if ( $response->status() == 200 ) {
            return response( 'Request sent to Event Controller' );
        }
        else {
            return $response;
        }

    }

    /**
     * @param $event array
     * @return array
     */
    private function getEventData( array $event ): array {
        return [
            'token' => env( 'VERIFICATION_TOKEN' ),
            'team_id' => 'T061EG9R6',
            'api_app_id' => 'A0MDYCDME',
            'event' => $event,
            'type' => 'event_callback',
            'event_id' => 'Ev0MDYHUEL',
            'event_time' => 1515449483000108,
            'authed_users' => [
                0 => 'U0LAN0Z89',
            ],
        ];
    }

    /**
     * Get App Mention event data
     *
     * @return string[]
     */
    private function getAppMentionEventData(): array {
        return [
            'type' => 'app_mention',
            'user' => 'W021FGA1Z',
            'text' => 'You can count on <@U0LAN0Z89> for an honorable mention.',
            'ts' => '1515449483.000108',
            'channel' => 'C0LAN2Q65',
            'event_ts' => '1515449483000108',
        ];
    }

    /**
     * Get Message event data
     * 50/50 change of returning standard message data or edited message data
     * @return array|string[]
     */
    private function getMessageEventData(): array {
        $data = [
            "type" => "message",
            "channel" => "C2147483705",
            "user" => "U2147483697",
            "text" => "Hello world",
            "ts" => "1355517523.000005"
        ];
        if ( rand( 0, 1 ) == 0 ) {
            return $data;
        }
        else {
            $data[ 'edited' ] = [
                "user" => "U2147483697",
                "ts" => "1355517536.000001"
            ];
            return $data;
        }
    }
}
