<?php

namespace App\Http\Controllers;

use App\Helpers\DebugHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Helpers\ResponseHelper;
use App\Slack\SlackUserData;

class UserController extends Controller {
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
	public function update( Request $request, $user_id ) {
		
		$data = $request->json();
		
		if ( empty( $data->all() ) ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), 'No JSON body in request' );
		}
		
		$userUpdated = $this->handleUserRequestData( $data, 'name', 'string' );
		$userUpdated .= $this->handleUserRequestData( $data, 'username', 'string' );
		$userUpdated .= $this->handleUserRequestData( $data, 'active', 'bool' );
		$userUpdated .= $this->handleUserRequestData( $data, 'total_received', 'int' );
		$userUpdated .= $this->handleUserRequestData( $data, 'total_given', 'int' );
		$userUpdated .= $this->handleUserRequestData( $data, 'total_redeemable', 'int' );
		
		if ( !$userUpdated ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), sprintf( 'User %s unable to be updated in database', $user_id ), 500 );
		}
		
		Log::info( sprintf( 'User %s successfully updated', $user_id ) );
		return response( sprintf( 'Updated %s user in database', $user_id ) );
	}
	
	/**
	 * @param $data
	 * @param string $value
	 * @param string $data_type
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
	 */
	private function handleUserRequestData( $data, string $value, string $data_type = '' ) {
		if ( !$data->has( $value ) ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), sprintf( 'Data does not contain %s', $value ) );
		}
		
		switch ( $data_type ) {
			case 'string':
				$is_data_type = is_string( $data->get( $value ) );
				break;
			case 'bool':
				$is_data_type = is_bool( $data->get( $value ) );
				break;
			case 'int':
				$is_data_type = is_int( $data->get( $value ) );
				break;
			default:
				$is_data_type = false;
		}
		
		if ( !$is_data_type ) {
			return ResponseHelper::logAndSendErrorResponse( DebugHelper::get_called_method(), sprintf( 'Data %s is not data type %s', $value, $data_type ) );
		}
		
		return User::where( $value, $data->get( $value ) )
			->update( [ $value => $data->get( $value ) ] );
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
