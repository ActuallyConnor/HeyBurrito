<?php

namespace App\Http\Requests;

use App\Http\Middleware\SlackEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class EventPostRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'event' => 'array',
            'user_id' => 'string',
            'channel_id' => 'string',
            'text' => 'string'
        ];
    }

}
