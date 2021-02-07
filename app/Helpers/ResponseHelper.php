<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ResponseHelper {
	/**
	 * Handles logging and response for bad request
	 * @param string $dataAttrKey
	 * @param string $message
	 * @param int $statusCode
	 * @param string $class_name
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public static function logAndSendErrorResponse( $class_name = '', $message = '', $statusCode = 400 ) {
		Log::error( print_r( [
			$statusCode,
			'Error in ' . $class_name,
			$message,
		], true ) );
		return response( $message, $statusCode );
	}
}
