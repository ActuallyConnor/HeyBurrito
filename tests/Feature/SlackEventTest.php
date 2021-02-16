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
}
