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
		//
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
		
		if (!$user_info) {
			return ResponseHelper::logAndSendErrorResponse( '', 'Unable to get userdata from slack', 500 );
		}
		
		$user = new User();
		
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
		return response( 'User successfully added' );
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
		//
	}
}
