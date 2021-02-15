<?php

namespace App\Http\Controllers;

use App\Helpers\DebugHelper;
use App\Http\Middleware\TokenAuth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
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
    public function index(): Response {
        return response( 'Index is working' );
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'username' => [
                'bail',
                'required',
                'string',
                'max:255'
            ]
        ] );

        if ( $validator->fails() ) {
            return response( $validator->getMessageBag(), 500 );
        }

        $username = $validator->validated()[ 'username' ];
        $user_info = SlackUserData::getUserInformationFromSlack( $username );

        if ( !$user_info ) {
            return ResponseHelper::logAndErrorResponse( 'Unable to get userdata from slack', 500 );
        }

        $user = new User();

        if ( !empty( $user->where( 'user_id', $user_info->id )->first() ) ) {
            return ResponseHelper::logAndErrorResponse( 'User already exists in the database', 409 );
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
            return ResponseHelper::logAndErrorResponse( 'User unable to be added to database', 500 );
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update( Request $request, $user_id ): Response {

        $data = $request->json();

        if ( empty( $data->all() ) ) {
            return ResponseHelper::logAndErrorResponse( 'No JSON body in request' );
        }

        $validator = Validator::make( $request->all(), [
            'name' => [ 'string' ],
            'username' => [ 'string' ],
            'active' => [ 'boolean' ],
            'total_received' => [ 'integer' ],
            'total_given' => [ 'integer' ],
            'total_redeemable' => [ 'integer' ]
        ] );

        if ( $validator->fails() ) {
            return response( $validator->getMessageBag(), 500 );
        }

        $validated = $validator->validated();
        foreach ( $validated as $key => $value ) {
            User::where( 'user_id', $user_id )
                ->update( [ $key => $value ] );
        }

        Log::info( sprintf( 'User %s successfully updated', $user_id ) );
        return response( sprintf( print_r( [
            'Updated %s user in database'
        ], true ), $user_id ) );
    }

    /**
     * @param $user_id
     * @param $data
     * @param string $value
     * @return string
     */
    private function handleUserRequestData( $user_id, $data, string $value ): string {
        User::where( 'user_id', $user_id )
            ->update( [ $value => $data->get( $value ) ] );
        Log::info( sprintf( 'Data value %s has been updated', $value ) );
        return sprintf( 'Data value %s has been updated', $value );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $user_id
     * @return Response
     */
    public function destroy( $user_id ) {

        if ( empty( $user_id ) ) {
            return ResponseHelper::logAndErrorResponse( 'No id passed to destroy request' );
        }

        $user = new User();

        $user_from_db = $user->where( 'user_id', $user_id )->first();

        if ( empty( $user_from_db ) ) {
            return ResponseHelper::logAndErrorResponse( 'User does not exist in the database', 404 );
        }

        $user_from_db->delete();

        Log::info( sprintf( 'User %s removed from database', $user_id ) );
        return response( 'User removed from database' );
    }
}
