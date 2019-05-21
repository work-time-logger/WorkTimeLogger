<?php

namespace App\Domain\Employee\Events;

use Illuminate\Support\Carbon;
use Spatie\EventProjector\ShouldBeStored;

class EmployeeStartedWorking implements ShouldBeStored
{
    /**
     * @var string
     */
    public $time;

    /**
     * @var string
     */
    public $uuid;

    public function __construct(string $uuid, $time)
    {
        $this->time = $time instanceof Carbon ? $time->toIso8601String() : $time;
        $this->uuid = $uuid;
    }

    public function carbon()
    {
        return new Carbon($this->time);
    }
}
