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
		if ( !empty( User::where( 'user_id', 'UH8LSF3NV' )->first() ) ) {
			$delete_response = $this->json( 'DELETE', '/api/user/UH8LSF3NV' );
		}
		
		$response = $this->json( 'POST', '/api/user', [ 'username' => 'Actually Connor' ] );
		
		$response->assertStatus( 201 );
	}
	
	/**
	 * Test failing creating user because no JSON data
	 */
	public function testFailCreatingUserNoJson() {
		$response = $this->json( 'POST', '/api/user', [] );
		$response->assertStatus( 400 );
	}
	
	/**
	 * Test failing creating user because of bad username
	 */
	public function testFailCreatingUserBadUsername() {
		$response = $this->json( 'POST', '/api/user', [ 'username' => 'dopey' ] );
		$response->assertStatus( 500 );
	}
	
	/**
	 * Test failing creating user because of bad username
	 */
	public function testFailCreatingUserNoUsername() {
		$response = $this->json( 'POST', '/api/user', [ 'username' => '' ] );
		$response->assertStatus( 500 );
	}
	
	/**
	 * Test updating user in database
	 */
	public function testUpdateUser() {
		$response = $this->json( 'PATCH', '/api/user/Actually Connor', [ 'name' => 'Connor' ] );
		$response->assertStatus( 200 );
	}
	
	/**
	 *
	 */
	public function testUpdateUserDataValidation() {
		$response = $this->json( 'PATCH', '/api/user/UH8LSF3NV', [
			'name' => 'Connor',
			'username' => 'Actually Connor',
			'active' => true,
			'total_received' => 1,
			'total_given' => 1,
			'total_redeemable' => 1
		] );
		$response->assertStatus( 200 );
	}
	
	/**
	 * Test failing updating user because no username
	 */
	public function testFailUpdateUserNoUsername() {
		$response = $this->json( 'PATCH', '/api/user', [ 'name' => 'Connor' ] );
		$response->assertStatus( 405 );
	}
	
	/**
	 * Test fail to update user because of no JSON data
	 */
	public function testFailUpdateUserNoData() {
		$response = $this->json( 'PATCH', '/api/user/Actually Connor', [] );
		$response->assertStatus( 400 );
	}
	
	/**
	 * Test removing user from database
	 */
	public function testRemoveUser() {
		if ( empty( User::where( 'user_id', 'UH8LSF3NV' )->first() ) ) {
			$create_response = $this->json( 'POST', '/api/user', [ 'username' => 'Actually Connor' ] );
		}
		
		$response = $this->json( 'DELETE', '/api/user/UH8LSF3NV' );
		$response->assertStatus( 200 );
	}
	
	/**
	 * Test failing to remove user from database because of bad username
	 */
	public function testFailRemoveUserBadUsername() {
		$response = $this->json( 'DELETE', '/api/user/dopey' );
		$response->assertStatus( 404 );
	}
	
	/**
	 * Test failing to remove from database because of no username
	 */
	public function testFailRemoveUserNoUsername() {
		$response = $this->json( 'DELETE', '/api/user' );
		$response->assertStatus( 405 );
	}
}
