<?php

namespace App\Domain\Employee\Projectors;

use App\Domain\Employee\Events\EmployeeStoppedWorking;
use App\Domain\Employee\Events\EmployeeWorkedFor;
use App\Models\Employee;
use App\Models\WorkLog\DailySummary;
use App\Models\WorkLog\Entry;
use App\Models\WorkLog\OpenEntry;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;
use App\Domain\Employee\Events\EmployeeStartedWorking;

final class DailySummaryProjector implements Projector
{
    use ProjectsEvents;

    public function onEmployeeWorkedFor(EmployeeWorkedFor $event, string $aggregateUuid)
    {
        $employee = Employee::byUuid($aggregateUuid);
        
        $summary = $employee->DailySummaries()->firstOrNew([
            'day' => $event->day
        ]);
        $summary->worked_minutes += $event->minutes;
        $summary->save();
    }
    
    public function onStartingEventReplay()
    {
        DailySummary::truncate();
    }
}
