<?php

namespace App\Domain\Employee\Events;

use Illuminate\Support\Carbon;
use Spatie\EventProjector\ShouldBeStored;

class EmployeeStoppedWorking implements ShouldBeStored
{
    /**
     * @var string
     */
    public $time;

    /**
     * @var string
     */
    public $uuid;
    
    /**
     * @var string
     */
    public $scanner_uuid;

    public function __construct(string $uuid, $time, string $scanner_uuid = null)
    {
        $this->time = $time instanceof Carbon ? $time->toIso8601String() : $time;
        $this->uuid = $uuid;
        $this->scanner_uuid = $scanner_uuid;
    }

    public function carbon()
    {
        return new Carbon($this->time);
    }
}
