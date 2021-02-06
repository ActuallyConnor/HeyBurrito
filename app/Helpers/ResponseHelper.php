<?php


class ResponseHelper {
	/**
	 * Handles logging and response for bad request
	 * @param string $dataAttrKey
	 * @param string $customMessage
	 * @param int $statusCode
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public static function logAndSendErrorResponse( $dataAttrKey = '', $customMessage = '', $statusCode = 400 ) {
		Log::error( print_r( [
			'400',
			'Error in UserController::addUser()',
			!empty( $customMessage ) ? : sprintf( 'No %s in JSON body', $dataAttrKey )
		], true ) );
		return response( !empty( $customMessage ) ? : sprintf( 'No %s in JSON body', $dataAttrKey ), $statusCode );
	}
}
