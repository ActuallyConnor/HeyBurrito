<?php

namespace Tests\Feature;

use App\Http\Controllers\EventController;
use App\Models\Event;
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

    public function testCoreEventPostValidation() {
        $response = $this->json(
            'GET',
            '/api/slack/event/app_mention'
        );
        $response->assertStatus( 200 );
    }

    public function testCoreEventPostValidationFailure() {
        $response = $this->json(
            'POST',
            '/api/event',
            []
        );
        $response->assertStatus( 422 );
    }

    public function testAppMentionEvent() {
        $removeEvents = Event::where( 'type', 'app_mention' )
            ->where( 'user', 'W021FGA1Z' )
            ->delete();

        $response = $this->json(
            'GET',
            '/api/slack/event/app_mention'
        );

        $event = Event::where( 'type', 'app_mention' )
            ->where( 'user', 'W021FGA1Z' )
            ->get();

        $response->assertOk();
        $this->assertNotEmpty( $event );
        $this->assertEquals( 1, count( $event ) );
        $this->assertEquals( 'app_mention', $event[ 0 ]->type );
        $this->assertEquals( 'W021FGA1Z', $event[ 0 ]->user );
        $this->assertEquals( 'C0LAN2Q65', $event[ 0 ]->channel );
        $this->assertEquals( 'You can count on <@U0LAN0Z89> for an honorable mention.', $event[ 0 ]->text );
    }
}
