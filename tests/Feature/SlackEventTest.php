<?php

namespace Tests\Feature;

use App\Slack\Event\AppMention;
use App\Slack\Event\SlackEventFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SlackEventTest extends TestCase {

    public function testSlackEvent() {
        $slackEventFactory = new SlackEventFactory();
        $slackEvent = $slackEventFactory->createEvent( 'app_mention' );
        $this->assertInstanceOf( AppMention::class, $slackEvent );
    }

    public function testdataValidation() {
        $slackEventFactory = new SlackEventFactory();
        $slackEvent = $slackEventFactory->createEvent( 'app_mention' );

        $data = json_decode( '{
    "token": "ZZZZZZWSxiZZZ2yIvs3peJ",
    "team_id": "T061EG9R6",
    "api_app_id": "A0MDYCDME",
    "event": {
        "type": "app_mention",
        "user": "W021FGA1Z",
        "text": "You can count on <@U0LAN0Z89> for an honorable mention.",
        "ts": "1515449483.000108",
        "channel": "C0LAN2Q65",
        "event_ts": "1515449483000108"
    },
    "type": "event_callback",
    "event_id": "Ev0MDYHUEL",
    "event_time": 1515449483000108,
    "authed_users": [
        "U0LAN0Z89"
    ]
}', true );
        $slackEvent->validateData( $data );

        $this->assertTrue( $slackEvent->validated );
    }
}
