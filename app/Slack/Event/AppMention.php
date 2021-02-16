<?php


namespace App\Slack\Event;

use Illuminate\Support\Facades\Validator;

/**
 * Class AppMention - CONCRETE BUILDER
 * @package App\Slack\Event
 */
class AppMention extends SlackEvent {

    protected string $eventType;
    protected string $user;
    protected string $text;
    protected string $channel;

    public function validateEventData() {
        $validator = $this->runEventValidator();

        if ( $validator->fails() ) {
            // $this->validated is already set to false
            $this->errorMessage = $validator->getMessageBag();
        }
        else {
            $this->validated = true;
            $fields = $validator->validated();

            $this->eventType = $fields[ 'type' ];
            $this->user = $fields[ 'user' ];
            $this->text = $fields[ 'text' ];
            $this->channel = $fields[ 'channel' ];
        }
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator|void
     */
    private function runEventValidator() {
        if ( empty( $this->data[ 'event' ] ) ) {
            $this->validated = false;
            return;
        }

        $event = $this->data[ 'event' ];

        return Validator::make( $event, [
            'type' => [
                'bail',
                'required',
                'string'
            ],
            'user' => [
                'bail',
                'required',
                'string'
            ],
            'text' => [
                'bail',
                'required',
                'string'
            ],
//            'ts' => [
//                'bail',
//                'required',
//                'string'
//            ],
            'channel' => [
                'bail',
                'required',
                'string'
            ],
//            'event_ts' => [
//                'bail',
//                'required',
//                'string'
//            ]
        ] );
    }
}
