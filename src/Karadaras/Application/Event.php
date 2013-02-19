<?php

namespace Karadaras\Application;

use Zend\EventManager\Event as ZEvent;

class Event extends ZEvent
{
    const APP_EVENT_BOOT     = 'application.event.boot';
    const APP_EVENT_RESPONSE = 'application.event.response';
}