<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MockSlackAPITest extends TestCase {

    public function testSendingEventToEventController() {
        $response = $this->json( 'GET', '/api/slack/event/app_mention' );
        $response->assertStatus( 200 );
    }

}
