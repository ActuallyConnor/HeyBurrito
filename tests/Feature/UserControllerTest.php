<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\User;
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
	
	/**
	 * Test creating user in the database
	 */
	public function testCreatingUser() {
		// Delete user if they already exist before attempting to add them to the database
		$delete_response = $this->json( 'DELETE', '/api/user/Actually Connor' );
		
		$response = $this->json( 'POST', '/api/user', [ 'username' => 'Actually Connor' ] );
		
		$response->assertStatus( 201 );
	}
	
	public function testRemoveUserFromDatabase() {
		$response = $this->json( 'DELETE', '/api/user/Actually Connor' );
		$response->assertStatus( 200 );
	}
	
	public function testFailRemoveUserFromDatabase() {
		$response = $this->json( 'DELETE', '/api/user/dopey' );
		$response->assertStatus( 404 );
	}
}
