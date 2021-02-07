<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ResponseHelper {
	/**
	 * Handles logging and response for bad request
	 * @param string $class_name
	 * @param string $message
	 * @param int $statusCode
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public static function logAndSendErrorResponse( string $class_name = '', string $message = '', int $statusCode = 400 ) {
		Log::error( print_r( [
			$statusCode,
			'Error in ' . $class_name,
			$message,
		], true ) );
		return response( $message, $statusCode );
	}
}
