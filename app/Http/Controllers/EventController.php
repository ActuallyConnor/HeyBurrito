<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return response( 'GET /api/event' );
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store( Request $request ) {

        // Is this a challenge request to initiate Slack API endpoint?
        if ( $request->json()->has( 'challenge' ) ) {
            return response( [ 'challenge' => $request->json()->get( 'challenge' ) ] );
        }

        $validator = $this->validateSlackPostData( $request->all() );

        if ( $validator->fails() ) {
            return ResponseHelper::logAndErrorResponse( $validator->getMessageBag(), 500 );
        }

        $validated = $validator->validated();

        return response( 'POST /api/event' );
    }

    /**
     * Validate core data coming from Slack POST Event request
     *
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateSlackPostData( $data ) {
        return Validator::make( $data, [
            'api_app_id' => [
                'bail',
                'required',
                'string',
                function( $attr, $value, $fail ) {
                    if ( $value !== env( 'API_APP_ID' ) ) {
                        $fail( 'The ' . $attr . ' from Slack does not match what is expected' );
                    }
                }
            ],
            'event' => [
                'bail',
                'required',
                'array'
            ],
            'type' => [
                'bail',
                'required',
                'string',
            ],
            'event_id' => [
                'bail',
                'required',
                'string'
            ],
            'event_time' => [ // Unix time
                'bail',
                'required',
                'int'
            ],
            'authed_users' => [
                'bail',
                'required',
                function( $attr, $value, $fail ) {
                    if ( $value[ 0 ] !== env( 'AUTHED_USER' ) ) {
                        $fail( 'The ' . $attr . ' from Slack does not match what is expected' );
                    }
                }
            ]
        ] );
    }

    private function validateEventData( $eventData ) {

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
