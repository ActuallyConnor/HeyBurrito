<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\ResponseHelper;
use App\Slack\SlackUserData;

class UserController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$member = SlackUserData::getUserInformationFromSlack( 'Actually Connor' );
		var_dump( $member );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
		$data = $request->json();
		
		if ( empty( $data ) ) {
			return ResponseHelper::logAndSendErrorResponse( '' . 'No JSON body in request' );
		}
		if ( !$data->has( 'username' ) ) {
			return ResponseHelper::logAndSendErrorResponse( 'username' );
		}
		
		$username = $data->get( 'username' );
		$user_info = SlackUserData::getUserInformationFromSlack( $username );
		
		if ( !$user_info ) {
			return ResponseHelper::logAndSendErrorResponse( '', 'Unable to get userdata from slack', 500 );
		}
		
		$user = new User();
		
		if ( !empty( $user->where( 'user_id', $user_info->id )->first() ) ) {
			return ResponseHelper::logAndSendErrorResponse( '', 'User already exists in the database', 409 );
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
			return ResponseHelper::logAndSendErrorResponse( '', 'User unable to be added to database', 500 );
		}
		
		Log::info( 'User successfully added' );
		return response( 'User successfully added', 201 );
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id ) {
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit( $id ) {
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, $id ) {
		//
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {
		
		if ( empty( $id ) ) {
			return ResponseHelper::logAndSendErrorResponse( '' . 'No id passed to destroy request' );
		}
		
		$user = new User();
		
		$user_from_db = $user->where( 'username', $id )->first();
		
		if ( empty( $user_from_db ) ) {
			return ResponseHelper::logAndSendErrorResponse( '', 'User does not exist in the database', 404 );
		}
		
		$user_from_db->delete();
		
		
		Log::info( sprintf( 'User %s removed from database', $id ) );
		return response( 'User removed from database' );
	}
}
