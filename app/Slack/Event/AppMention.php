<?php


namespace App\Slack\Event;

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
        // TODO: Implement validateEventData() method.
    }
}
