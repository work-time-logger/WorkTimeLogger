<?php

namespace App\Domain\Employee\Events;

use Spatie\EventProjector\ShouldBeStored;

class EmployeeCreated implements ShouldBeStored
{
    /**
     * @var string
     */
    public $first_name;
    
    /**
     * @var string
     */
    public $last_name;

    public function __construct(string $first_name, string $last_name)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }
}
