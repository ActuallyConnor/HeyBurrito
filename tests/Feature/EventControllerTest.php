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

    public function testEventPostEndpoint() {
        $response = $this->json(
            'POST',
            '/api/event',
            []
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
}
