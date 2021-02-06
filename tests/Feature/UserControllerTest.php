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
	
	public function testCreatingUser() {
		// Delete user if they already exist before attempting to add them to the database
		$user = new User();
		$connor = $user->where( 'username', 'Actually Connor' )->first();
		if ( !empty( $connor ) ) {
			$connor->delete();
		}
		
		$response = $this->json( 'POST', '/api/user', [ 'username' => 'Actually Connor' ] );
		
		$response->assertStatus( 201 );
	}
}
