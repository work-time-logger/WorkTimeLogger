<?php

namespace App\Domain\Employee\Events;

use Spatie\EventProjector\ShouldBeStored;

class CardWasRegistered implements ShouldBeStored
{
    /**
     * @var string
     */
    public $identifier;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }
}
