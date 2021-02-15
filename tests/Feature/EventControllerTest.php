<?php

namespace Tests\Feature;

use App\Http\Controllers\EventController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventControllerTest extends TestCase {

    /**
     * Test creating event controller object
     */
    public function testCreatingEventControllerObject() {
        $this->assertIsObject( new EventController() );
    }

    public function testEventGetEndpoint() {
        $response = $this->json(
            'GET',
            '/api/event'
        );
        $response->assertStatus( 200 );
    }

    public function testCoreEventPostValidation() {
        $json_data = '{
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
}';
        $response = $this->json(
            'POST',
            '/api/event',
            json_decode( $json_data, true )
        );
        $response->assertStatus( 200 );
    }

    public function testCoreEventPostValidationFailure() {
        $response = $this->json(
            'POST',
            '/api/event',
            []
        );
        $response->assertStatus( 500 );
    }

    public function testSlackChallenge() {
        $challenge = 'this_is_the_challenge';
        $response = $this->json(
            'POST',
            '/api/event',
            [
                'challenge' => $challenge
            ]
        );
        $response
            ->assertStatus( 200 )
            ->assertJson( [
                'challenge' => $challenge
            ] );
    }
}
