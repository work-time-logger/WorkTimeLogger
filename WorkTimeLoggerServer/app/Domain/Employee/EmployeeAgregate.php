<?php

namespace App\Domain\Employee;

use App\Domain\Employee\Events\EmployeeCreated;
use Spatie\EventProjector\AggregateRoot;

final class EmployeeAgregate extends AggregateRoot
{
    public function createEmployee(string $first_name, string $last_name)
    {
        $this->recordThat(new EmployeeCreated($first_name, $last_name));

        return $this;
    }
}
