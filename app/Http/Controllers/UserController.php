<?php

namespace App\Http\Controllers;

use App\Helpers\DebugHelper;
use App\Http\Middleware\TokenAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Helpers\ResponseHelper;
use App\Slack\SlackUserData;

class UserController extends Controller {
	
	/**
	 * Instantiate a new controller instance.
	 * @return void
	 */
	public function __construct() {
		$this->middleware( TokenAuth::class );
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		var_dump( DebugHelper::get_called_method() );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		//
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store( Request $request ) {
		$data = $request->json();
		
		if ( empty( $data->all() ) ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), 'No JSON body in request' );
		}
		if ( !$data->has( 'username' ) ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), 'No username in JSON data' );
		}
		
		$username = $data->get( 'username' );
		$user_info = SlackUserData::getUserInformationFromSlack( $username );
		
		if ( !$user_info ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), 'Unable to get userdata from slack', 500 );
		}
		
		$user = new User();
		
		if ( !empty( $user->where( 'user_id', $user_info->id )->first() ) ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), 'User already exists in the database', 409 );
		}
		
		$user->name = $user_info->real_name;
		$user->username = $user_info->profile->display_name;
		$user->user_id = $user_info->id;
		$user->active = true;
		$user->total_received = 0;
		$user->total_given = 0;
		$user->total_redeemable = 0;
		
		$userAdded = $user->save();
		
		if ( !$userAdded ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), 'User unable to be added to database', 500 );
		}
		
		Log::info( 'User successfully added' );
		return response( 'User successfully added', 201 );
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function show( $id ) {
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit( $id ) {
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param $user_id
	 * @return Response
	 */
	public function update( Request $request, $user_id ): Response {
		
		$data = $request->json();
		
		if ( empty( $data->all() ) ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), 'No JSON body in request' );
		}
		
		$message_arr = array();
		array_push( $message_arr,
			$this->handleUserRequestData( $user_id, $data, 'name', 'string' ),
			$this->handleUserRequestData( $user_id, $data, 'username', 'string' ),
			$this->handleUserRequestData( $user_id, $data, 'active', 'boolean' ),
			$this->handleUserRequestData( $user_id, $data, 'total_received', 'integer' ),
			$this->handleUserRequestData( $user_id, $data, 'total_given', 'integer' ),
			$this->handleUserRequestData( $user_id, $data, 'total_redeemable', 'integer' )
		);
		
		Log::info( sprintf( 'User %s successfully updated', $user_id ) );
		return response( sprintf( print_r( [
			'Updated %s user in database'
		], true ), $user_id ) );
	}
	
	/**
	 * @param $user_id
	 * @param $data
	 * @param string $value
	 * @param string $data_type
	 * @return string
	 */
	private function handleUserRequestData( $user_id, $data, string $value, string $data_type = '' ): string {
		if ( $data->has( $value ) ) {
			if ( gettype( $data->get( $value ) === $data_type ) ) {
				User::where( 'user_id', $user_id )
					->update( [ $value => $data->get( $value ) ] );
				Log::info( sprintf( 'Data value %s has been updated', $value ) );
				return sprintf( 'Data value %s has been updated', $value );
			} else {
				Log::error( sprintf( 'Data value %s does not equal data type %s', $value, $data_type ) );
				return sprintf( 'Data value %s does not equal data type %s', $value, $data_type );
			}
		} else {
			Log::info( sprintf( 'Data value %s not in JSON body', $value ) );
			return sprintf( 'Data value %s not in JSON body', $value );
		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $user_id
	 * @return Response
	 */
	public function destroy( $user_id ) {
		
		if ( empty( $user_id ) ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), 'No id passed to destroy request' );
		}
		
		$user = new User();
		
		$user_from_db = $user->where( 'user_id', $user_id )->first();
		
		if ( empty( $user_from_db ) ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), 'User does not exist in the database', 404 );
		}
		
		$user_from_db->delete();
		
		
		Log::info( sprintf( 'User %s removed from database', $user_id ) );
		return response( 'User removed from database' );
	}
}
