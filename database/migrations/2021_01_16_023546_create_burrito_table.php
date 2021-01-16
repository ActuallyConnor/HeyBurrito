<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBurritoTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'burrito', function( Blueprint $table ) {
			$table->id();
			$table->timestamps();
			$table->string( 'burrito_giver' );
			$table->string( 'burrito_receiver' );
			$table->boolean( 'message_sent_to_giver' );
			$table->boolean( 'message_sent_to_receiver' );
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists( 'burrito' );
	}
}
