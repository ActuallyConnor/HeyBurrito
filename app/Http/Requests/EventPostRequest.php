<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class EventPostRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [];
    }

    public function validate() {
        return Validator::make( $this->request->all(), [
            'token' => [
                'bail',
                'required',
                'string',
                function( $attr, $value, $fail ) {
                    if ( $value !== env( 'VERIFICATION_TOKEN' ) ) {
                        $fail( sprintf( '%s does not match what is expected', $attr ) );
                    }
                }
            ],
            'event' => [ 'array' ],
            'user_id' => [ 'string' ],
            'channel_id' => [ 'string' ],
            'text' => [ 'string' ]
        ] );
    }
}
