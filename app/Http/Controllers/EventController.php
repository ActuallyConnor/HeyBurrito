<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Slack\Event\SlackEventFactory;
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

        $slackEventFactory = new SlackEventFactory();
        $slackEvent = $slackEventFactory->createEvent( 'app_mention' );

        $slackEvent->validateData( $request->all() );

        if ( !$slackEvent->validated ) {
            return ResponseHelper::logAndErrorResponse( $slackEvent->errorMessage, 500 );
        }

        return response( 'POST /api/event' );
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
