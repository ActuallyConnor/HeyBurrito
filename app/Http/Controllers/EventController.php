<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Middleware\SlackChallengeMiddleware;
use App\Http\Requests\EventPostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller {

    public function __construct() {
        $this->middleware( SlackChallengeMiddleware::class );

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
        $validator = $request->validate();
        if ( $validator->fails() ) {
            return ResponseHelper::logAndErrorResponse( $validator->getMessageBag(), 500 );
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
