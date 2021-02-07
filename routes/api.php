<?php

use App\Http\Middleware\TokenAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BurritoController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware( 'auth:api' )->get( '/user', function( Request $request ) {
	return $request->user();
} );

Route::post( '/burrito', [ BurritoController::class, 'giveBurrito' ] );

Route::resource( 'user', UserController::class )
	->except( [
		'create', 'show', 'edit'
	] );
