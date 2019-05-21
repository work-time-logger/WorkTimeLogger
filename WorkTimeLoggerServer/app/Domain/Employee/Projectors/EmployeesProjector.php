<?php

namespace App\Domain\Employee\Projectors;

use App\Domain\Employee\Events\EmployeeCreated;
use App\Models\Employee;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class EmployeesProjector implements Projector
{
    use ProjectsEvents;

    public function onEmployeeCreated(EmployeeCreated $event, string $aggregateUuid)
    {
        $employee = new Employee;
        $employee->uuid = $aggregateUuid;
        $employee->first_name = $event->first_name;
        $employee->last_name = $event->last_name;
        $employee->save();
    }
}
