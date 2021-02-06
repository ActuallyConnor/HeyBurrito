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
			$table->string( 'username' );
			$table->string( 'user_id' );
			$table->boolean( 'active' )->default( true );
			$table->integer( 'total_received' )->default( 0 );
			$table->integer( 'total_given' );
			$table->integer( 'total_redeemable' )->default( 0 );
			$table->integer( 'burritos_left_today' )->default( 0 );
			
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
