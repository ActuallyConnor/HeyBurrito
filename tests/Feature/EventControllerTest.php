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
        $response->assertStatus( 500 );
    }

    public function testAppMentionEvent() {
        $event_type = 'app_mention';

        $response = $this->getEventTypeResponse( $event_type );
        $event = $this->getEventTypeInDatabase( $event_type );

        $response->assertOk();
        $this->assertNotEmpty( $event );
        $this->assertCount( 1, $event );
        $this->assertEquals( $event_type, $event[ 0 ]->type );
        $this->assertEquals( 'W021FGA1Z', $event[ 0 ]->user );
        $this->assertEquals( 'C0LAN2Q65', $event[ 0 ]->channel );
        $this->assertEquals( 'You can count on <@U0LAN0Z89> for an honorable mention.', $event[ 0 ]->text );
    }

    public function testMessageEvent() {
        $event_type = 'message';

        $response = $this->getEventTypeResponse( $event_type );
        $event = $this->getEventTypeInDatabase( $event_type );

        $response->assertOk();
        $this->assertNotEmpty( $event );
        $this->assertCount( 1, $event );
        $this->assertEquals( $event_type, $event[ 0 ]->type );
        $this->assertEquals( 'U2147483697', $event[ 0 ]->user );
        $this->assertEquals( 'C2147483705', $event[ 0 ]->channel );
        $this->assertEquals( 'Hello world', $event[ 0 ]->text );
    }

    public function testSlashCommandEvent() {
        $event_type = 'slash_command';

        $response = $this->getEventTypeResponse( $event_type );
        $event = $this->getEventTypeInDatabase( $event_type );

        $response->assertOk();
        $this->assertNotEmpty( $event );
        $this->assertCount( 1, $event );
        $this->assertEquals( $event_type, $event[ 0 ]->type );
        $this->assertEquals( 'U2147483697', $event[ 0 ]->user );
        $this->assertEquals( 'C2147483705', $event[ 0 ]->channel );
        $this->assertEquals( 'Hey Burrito!', $event[ 0 ]->text );
    }

    /**
     * Get response from mock API
     *
     * @param string $event_type
     * @return \Illuminate\Testing\TestResponse
     */
    private function getEventTypeResponse( string $event_type ) {
        $removeEvents = Event::where( 'type', $event_type )
            ->delete();

        return $this->json(
            'GET',
            sprintf( '/api/slack/event/%s', $event_type )
        );
    }

    /**
     * Get database row
     *
     * @param string $event_type
     * @return mixed
     */
    private function getEventTypeInDatabase( string $event_type ) {
        return Event::where( 'type', $event_type )
            ->get();
    }
}
