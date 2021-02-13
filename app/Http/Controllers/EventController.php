<?php

namespace App\Http\Controllers;

use App\Helpers\DebugHelper;
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
     */
    public function store( Request $request ) {
        $data = $request->json();
        if ( $data->has( 'challenge' ) ) {
            return $this->slackChallenge( $request );
        }
        return response( 'POST /api/event' );
    }

    private function slackChallenge( Request $request ) {

        $validator = Validator::make( $request->all(), [
            'challenge' => [
                'bail',
                'required',
                'string'
            ]
        ] );

        if ( $validator->fails() ) {
            return ResponseHelper::logAndErrorResponse( 'Unable to get userdata from slack', 500 );
        }

        return response( ['challenge' => $request->json()->get( 'challenge' )] );
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
