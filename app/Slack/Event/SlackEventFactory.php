<?php


namespace App\Slack\Event;


class SlackEventFactory extends AbstractSlackEventFactory {

    function createEvent( $type ) {
        $event = SlackEvent::class;
        switch ( $type ) {
            case 'app_mention':
                $event = new AppMention();
                break;
        }
        return $event;
    }
}
