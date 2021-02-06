<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase {
	
	/**
	 * Test creating user controller object
	 */
	public function testCreateUserControllerObject() {
		$this->assertIsObject( new UserController() );
	}
	
	public function testCreatingUser() {
		$response = $this->json( 'POST', '/api/user', [ 'username' => 'Actually Connor' ] );
		
		$response->assertStatus( 200 );
	}
}
