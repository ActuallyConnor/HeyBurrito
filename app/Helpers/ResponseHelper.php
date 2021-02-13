<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ResponseHelper {
	/**
	 * Handles logging and response for bad request
     *
	 * @param string $message
	 * @param int $statusCode
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public static function logAndErrorResponse( string $message = '', int $statusCode = 400 ) {
		Log::error( print_r( [
			$statusCode,
			'Error in ' . self::get_called_method(),
			$message,
		], true ) );
		return response( $message, $statusCode );
	}

    private static function get_called_method(): string {
        $debug = debug_backtrace()[ 1 ];
        return $debug[ 'class' ] . '::' . $debug[ 'function' ];
    }
}
