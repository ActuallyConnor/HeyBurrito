<?php


namespace App\Slack\Event;

use Illuminate\Support\Facades\Validator;

/**
 * Class SlackEvent - BUILDER
 * @package App\Slack\Event
 */
abstract class SlackEvent {

    /**
     * @var array
     */
    protected array $data;

    /**
     * @var bool
     */
    public bool $validated = false;

    /**
     * @var string
     */
    public string $errorMessage = '';

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var int
     */
    protected int $timestamp;

    /**
     * @var string
     */
    protected string $id;

    // Force Extending class to define this method
    abstract public function validateEventData();

    public function validateData( $data ) {
        $this->data = $data;
        $this->validateCoreData();
        $this->validateEventData();
    }

    /**
     * Validate core data coming from Slack POST Event request
     */
    public function validateCoreData() {
        $validator = $this->runCoreValidator();

        if ( $validator->fails() ) {
            // $this->validated is already set to false
            $this->errorMessage = $validator->getMessageBag();
        }
        else {
            $this->validated = true;
            $fields = $validator->validated();

            $this->type = $fields[ 'type' ];
            $this->timestamp = $fields[ 'event_time' ];
            $this->id = $fields[ 'event_id' ];
        }
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function runCoreValidator() {
        return Validator::make( $this->data, [
            'api_app_id' => [
                'bail',
                'required',
                'string',
                function( $attr, $value, $fail ) {
                    if ( $value !== env( 'API_APP_ID' ) ) {
                        $fail( 'The ' . $attr . ' from Slack does not match what is expected' );
                    }
                }
            ],
            'event' => [
                'bail',
                'required',
                'array'
            ],
            'type' => [
                'bail',
                'required',
                'string',
            ],
            'event_id' => [
                'bail',
                'required',
                'string'
            ],
            'event_time' => [ // Unix time
                'bail',
                'required',
                'int'
            ],
            'authed_users' => [
                'bail',
                'required',
                function( $attr, $value, $fail ) {
                    if ( $value[ 0 ] !== env( 'AUTHED_USER' ) ) {
                        $fail( 'The ' . $attr . ' from Slack does not match what is expected' );
                    }
                }
            ]
        ] );
    }
}
