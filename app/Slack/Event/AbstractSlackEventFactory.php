<?php


namespace App\Slack\Event;


abstract class AbstractSlackEventFactory {
    abstract function createEvent( $type );
}
