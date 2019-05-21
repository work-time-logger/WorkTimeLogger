<?php

namespace App\Domain\Employee\Events;

use Illuminate\Support\Carbon;
use Spatie\EventProjector\ShouldBeStored;

class EmployeeWorkedFor implements ShouldBeStored
{
    /**
     * @var string
     */
    public $entry_uuid;
    
    /**
     * @var string
     */
    public $day;
    
    /**
     * @var int
     */
    public $minutes;

    public function __construct(string $entry_uuid, string $day, int $minutes)
    {
        $this->entry_uuid = $entry_uuid;
        $this->day = $day;
        $this->minutes = $minutes;
    }
}
