<?php


namespace App\Helpers;


class DebugHelper {
	public static function get_called_method() {
		$debug = debug_backtrace()[ 1 ];
		return $debug[ 'class' ] . '::' . $debug[ 'function' ];
	}
}