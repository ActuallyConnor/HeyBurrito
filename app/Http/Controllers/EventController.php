<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Middleware\SlackEvent;
use App\Http\Requests\EventPostRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller {

    public function __construct() {
        $this->middleware( SlackEvent::class, [
            'only' => [ 'store' ]
        ] );

    }

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
    public function store( EventPostRequest $request ) {
        $validated = $request->validated();

        $event = new Event();

        if ( !empty( $validated[ 'event' ] ) ) {
            $event->type = $validated[ 'event' ][ 'type' ];
            $event->user = $validated[ 'event' ][ 'user' ]; // could be user_id but also user element could be an array
            $event->channel = $validated[ 'event' ][ 'channel' ];
            $event->text = $validated[ 'event' ][ 'text' ];
        } else {
            $event->type = 'slash_command';
            $event->user = $validated[ 'user_id' ];
            $event->channel = $validated[ 'channel_id' ];
            $event->text = $validated[ 'text' ];
        }

        $event->save();

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
