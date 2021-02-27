<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MockSlackAPI extends Controller {

    public function event( $eventType ) {

        $eventUrl = sprintf( '%s/api/event', env( 'APP_URL' ) );

        switch ( $eventType ) {
            case 'app_mention':
                $response = Http::post(
                    $eventUrl,
                    $this->getEventData( $this->getAppMentionEventData() ) );
                break;
            case 'message':

                $response = Http::post(
                    $eventUrl,
                    $this->getEventData( $this->getMessageEventData() ) );
                break;
            case 'slash_command':
                $response = Http::asForm()->post(
                    $eventUrl,
                    $this->getSlashCommandEventData()
                );
                break;
            default:
                $response = response( '', 500 );
                break;
        }

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

    /**
     * Get Slash Command event data
     * Slack give you the data as URL encoded so in the calling function for this function we send the request using
     * Http::asForm()
     * @return array
     */
    private function getSlashCommandEventData(): array {
        return [
            'token' => env( 'VERIFICATION_TOKEN' ),
            'team_id' => 'T0001',
            'team_domain' => 'example',
            'enterprise_id' => 'E0001',
            'enterprise_name' => 'Globular%20Construct%20Inc',
            'channel_id' => 'C2147483705',
            'channel_name' => 'test',
            'user_id' => 'U2147483697',
            'user_name' => 'Steve',
            'command' => '/heyburrito',
            'text' => 'Hey Burrito!',
            'response_url' => 'https://hooks.slack.com/commands/1234/5678',
            'trigger_id',
            '13345224609.738474920.8088930838d88f008e0',
            'api_app_id',
            env( 'API_APP_ID' )
        ];
    }

    public function users_list() {
        $data = [
            "ok" => true,
            "members" => [
                [
                    "id" => "UH8LSF3NV",
                    "team_id" => "T028FDZ8T",
                    "name" => "connor",
                    "deleted" => false,
                    "color" => "bc3663",
                    "real_name" => "Connor",
                    "tz" => "America/New_York",
                    "tz_label" => "Eastern Standard Time",
                    "tz_offset" => -18000,
                    "profile" => [
                        "title" => "typey typey",
                        "phone" => "4165245596",
                        "skype" => "",
                        "real_name" => "Connor",
                        "real_name_normalized" => "Connor",
                        "display_name" => "Actually Connor",
                        "display_name_normalized" => "Actually Connor",
                        "fields" => null,
                        "status_text" => "",
                        "status_emoji" => "",
                        "status_expiration" => 1610081999,
                        "avatar_hash" => "576b8d9257e1",
                        "image_original" => "https://avatars.slack-edge.com/2021-01-20/1682568248256_576b8d9257e166bdf82f_original.png",
                        "is_custom_image" => true,
                        "email" => "connor@artscience.ca",
                        "first_name" => "Connor",
                        "last_name" => "",
                        "image_24" => "https://avatars.slack-edge.com/2021-01-20/1682568248256_576b8d9257e166bdf82f_24.png",
                        "image_32" => "https://avatars.slack-edge.com/2021-01-20/1682568248256_576b8d9257e166bdf82f_32.png",
                        "image_48" => "https://avatars.slack-edge.com/2021-01-20/1682568248256_576b8d9257e166bdf82f_48.png",
                        "image_72" => "https://avatars.slack-edge.com/2021-01-20/1682568248256_576b8d9257e166bdf82f_72.png",
                        "image_192" => "https://avatars.slack-edge.com/2021-01-20/1682568248256_576b8d9257e166bdf82f_192.png",
                        "image_512" => "https://avatars.slack-edge.com/2021-01-20/1682568248256_576b8d9257e166bdf82f_512.png",
                        "image_1024" => "https://avatars.slack-edge.com/2021-01-20/1682568248256_576b8d9257e166bdf82f_1024.png",
                        "status_text_canonical" => "",
                        "team" => "T028FDZ8T"
                    ],
                    "is_admin" => false,
                    "is_owner" => false,
                    "is_primary_owner" => false,
                    "is_restricted" => false,
                    "is_ultra_restricted" => false,
                    "is_bot" => false,
                    "is_app_user" => false,
                    "updated" => 1614369621,
                    "is_email_confirmed" => true
                ],
                [
                    "id" => "U2ZPWV0JE",
                    "team_id" => "T028FDZ8T",
                    "name" => "jordana",
                    "deleted" => false,
                    "color" => "b14cbc",
                    "real_name" => "Jordana Harrison",
                    "tz" => "America/New_York",
                    "tz_label" => "Eastern Standard Time",
                    "tz_offset" => -18000,
                    "profile" => [
                        "title" => "Senior Full Snacc Developer",
                        "phone" => "4167882607",
                        "skype" => "",
                        "real_name" => "Jordana Harrison",
                        "real_name_normalized" => "Jordana Harrison",
                        "display_name" => "banana",
                        "display_name_normalized" => "banana",
                        "fields" => null,
                        "status_text" => "",
                        "status_emoji" => "",
                        "status_expiration" => 0,
                        "avatar_hash" => "g4744eca4135",
                        "email" => "jordana@artscience.ca",
                        "first_name" => "Jordana",
                        "last_name" => "Harrison",
                        "image_24" => "https://secure.gravatar.com/avatar/4744eca4135a6ff4615e066d33d1b633.jpg?s=24&d=https%3A%2F%2Fa.slack-edge.com%2Fdf10d%2Fimg%2Favatars%2Fava_0019-24.png",
                        "image_32" => "https://secure.gravatar.com/avatar/4744eca4135a6ff4615e066d33d1b633.jpg?s=32&d=https%3A%2F%2Fa.slack-edge.com%2Fdf10d%2Fimg%2Favatars%2Fava_0019-32.png",
                        "image_48" => "https://secure.gravatar.com/avatar/4744eca4135a6ff4615e066d33d1b633.jpg?s=48&d=https%3A%2F%2Fa.slack-edge.com%2Fdf10d%2Fimg%2Favatars%2Fava_0019-48.png",
                        "image_72" => "https://secure.gravatar.com/avatar/4744eca4135a6ff4615e066d33d1b633.jpg?s=72&d=https%3A%2F%2Fa.slack-edge.com%2Fdf10d%2Fimg%2Favatars%2Fava_0019-72.png",
                        "image_192" => "https://secure.gravatar.com/avatar/4744eca4135a6ff4615e066d33d1b633.jpg?s=192&d=https%3A%2F%2Fa.slack-edge.com%2Fdf10d%2Fimg%2Favatars%2Fava_0019-192.png",
                        "image_512" => "https://secure.gravatar.com/avatar/4744eca4135a6ff4615e066d33d1b633.jpg?s=512&d=https%3A%2F%2Fa.slack-edge.com%2Fdf10d%2Fimg%2Favatars%2Fava_0019-512.png",
                        "status_text_canonical" => "",
                        "team" => "T028FDZ8T"
                    ],
                    "is_admin" => false,
                    "is_owner" => false,
                    "is_primary_owner" => false,
                    "is_restricted" => false,
                    "is_ultra_restricted" => false,
                    "is_bot" => false,
                    "is_app_user" => false,
                    "updated" => 1614185145,
                    "is_email_confirmed" => true
                ]
            ],
            "cache_ts" => 1498777272,
            "response_metadata" => [
                "next_cursor" => ""
            ]
        ];

        return response( $data, 200 );
    }
}
