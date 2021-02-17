<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventPostRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'challenge' => 'string',
            'token' => 'string',
            'api_app_id' => 'string',
            'event' => 'array',
            'event_id' => 'string',
            'event_time' => 'int' // UNIX time
        ];
    }
}
