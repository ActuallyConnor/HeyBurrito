<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase {

	/**
	 * Test create user controller object
	 */
	public function testCreateUserControllerObject() {
		$this->assertIsObject( new UserController() );
	}

	/**
	 * Test index resolves when token is provided
	 */
	public function testUserControllerIndex() {
		$response = $this->json(
			'GET',
			'/api/user',
			[],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 200 );
	}

	/**
	 * Test index redirects when incorrect token is provided
	 */
	public function testFailToUserControllerIndexBadAuth() {
		$response = $this->json(
			'GET',
			'/api/user',
			[],
			[ 'hey-burrito-token' => strrev( env( 'HEY_BURRITO_AUTH_TOKEN' ) ) ]
		);
		$response->assertStatus( 302 );
	}

	/**
	 * Test index redirects when no token is provided
	 */
	public function testFailToUserControllerIndexNoAuth() {
		$response = $this->json(
			'GET',
			'/api/user',
			[],
		);
		$response->assertStatus( 302 );
	}

	/**
	 * Test create user in the database
	 */
	public function testCreatingUser() {
		// Delete user if they already exist before attempting to add them to the database
		if ( !empty( User::where( 'user_id', 'UH8LSF3NV' )->first() ) ) {
			$delete_response = $this->json(
				'DELETE',
				'/api/user/UH8LSF3NV',
				[],
				[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
			);
		}

		$response = $this->json(
		    'POST',
			'/api/user',
			[ 'username' => 'Actually Connor' ],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);

		$response->assertStatus( 201 );
	}

	/**
	 * Test fail to create user because no JSON data
	 */
	public function testFailToCreatingUserNoJson() {
		$response = $this->json(
			'POST',
			'/api/user',
			[],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 500 );
	}

	/**
	 * Test fail to create user because of bad username
	 */
	public function testFailToCreatingUserBadUsername() {
		$response = $this->json(
		    'POST',
			'/api/user',
			[ 'username' => 'dopey' ],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 500 );
	}

	/**
	 * Test fail to create user because of bad username
	 */
	public function testFailToCreatingUserNoUsername() {
		$response = $this->json(
			'POST',
			'/api/user',
			[ 'username' => '' ],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 500 );
	}

    /**
     *
     */
    public function testFailToCreateUserThatAlreadyExists() {
        if ( empty( User::where( 'user_id', 'UH8LSF3NV' )->first() ) ) {
            $create_response = $this->json(
                'POST',
                '/api/user',
                [ 'username' => 'Actually Connor' ],
                [ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
            );
        }

        $response = $this->json(
            'POST',
            '/api/user',
            [ 'username' => 'Actually Connor' ],
            [ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
        );

        $response->assertStatus( 409 );
    }

	/**
	 * Test updating user in database
	 */
	public function testUpdateUser() {
		$response = $this->json(
			'PATCH',
			'/api/user/Actually Connor',
			[ 'name' => 'Connor' ],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 200 );
	}

	/**
	 * Test update user data validation
	 */
	public function testUpdateUserDataValidation() {
		$response = $this->json(
			'PATCH',
			'/api/user/UH8LSF3NV',
			[
				'name' => 'Connor',
				'username' => 'Actually Connor',
				'active' => true,
				'total_received' => 1,
				'total_given' => 1,
				'total_redeemable' => 1
			],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 200 );
	}

	/**
	 * Test that it does not break when we fail data validation
	 */
	public function testDoesNotBreakWhenUpdatingUserDataFailsValidation() {
		$response = $this->json(
			'PATCH',
			'/api/user/UH8LSF3NV',
			[ 'bad_field' => 'dopey', ],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 200 );
	}

	/**
	 * Test fail to updating user because no username
	 */
	public function testFailToUpdateUserNoUsername() {
		$response = $this->json(
			'PATCH',
			'/api/user',
			[ 'name' => 'Connor' ],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 405 );
	}

	/**
	 * Test fail to update user because of no JSON data
	 */
	public function testFailToUpdateUserNoData() {
		$response = $this->json(
			'PATCH',
			'/api/user/Actually Connor',
			[],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 400 );
	}

	/**
	 * Test removing user from database
	 */
	public function testRemoveUser() {
		if ( empty( User::where( 'user_id', 'UH8LSF3NV' )->first() ) ) {
			$create_response = $this->json(
				'POST',
				'/api/user',
				[ 'username' => 'Actually Connor' ],
				[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
			);
		}

		$response = $this->json(
			'DELETE',
			'/api/user/UH8LSF3NV',
			[],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 200 );
	}

	/**
	 * Test fail to remove user from database because of bad username
	 */
	public function testFailToRemoveUserBadUsername() {
		$response = $this->json(
			'DELETE',
			'/api/user/dopey',
			[],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 404 );
	}

	/**
	 * Test fail to remove from database because of no username
	 */
	public function testFailToRemoveUserNoUsername() {
		$response = $this->json(
			'DELETE',
			'/api/user',
			[],
			[ 'hey-burrito-token' => env( 'HEY_BURRITO_AUTH_TOKEN' ) ]
		);
		$response->assertStatus( 405 );
	}
}
