<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MockSlackAPITest extends TestCase {

    public function testSendingEventToEventController() {
        $event_type = 'app_mention';

        $removeEvents = Event::where( 'type', $event_type )
            ->delete();

        $response = $this->json( 'GET', sprintf( '/api/slack/event/%s', $event_type ) );
        $response->assertStatus( 200 );
    }

    public function testUsersListEndpoint() {
        $response = $this->json( 'GET', '/api/slack/users.list' );
        $response->assertStatus( 200 );
        $this->assertTrue( $response->json( 'ok' ) );
        $this->assertIsArray( $response->json( 'members' ) );
        $this->assertNotEmpty( $response->json( 'members' ) );
    }

}
