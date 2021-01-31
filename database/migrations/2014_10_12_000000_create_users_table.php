<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'users', function( Blueprint $table ) {
			$table->id();
			$table->string( 'name' );
			$table->string( 'email' )->unique();
			$table->timestamp( 'email_verified_at' )->nullable();
			$table->string( 'password' );
			$table->string( 'username' );
			$table->string( 'user_id' );
			$table->boolean( 'active' );
			$table->integer( 'total_received' );
			$table->integer( 'total_given' );
			$table->integer( 'total_redeemable' );
			$table->integer( 'burritos_left_today' );
			
			$table->rememberToken();
			$table->timestamps();
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists( 'users' );
	}
}
