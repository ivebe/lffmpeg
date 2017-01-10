<?php

namespace Ivebe\Lffmpeg\Events;

use Illuminate\Queue\SerializesModels;
use Ivebe\Lffmpeg\Events\Contracts\IProcessVideoEvent;

/**
 * Class Event
 *
 * Only purpose is to ensure that class which extend it must implement SerializesModels trait
 *
 * @package Ivebe\Lffmpeg\Events
 */
abstract class Event implements IProcessVideoEvent
{
    use SerializesModels;
}
