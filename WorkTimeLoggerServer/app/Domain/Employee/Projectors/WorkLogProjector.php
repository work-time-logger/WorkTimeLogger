<?php

namespace App\Domain\Employee\Projectors;

use App\Domain\Employee\Events\EmployeeStoppedWorking;
use App\Domain\Employee\Events\EmployeeWorkedFor;
use App\Models\Employee;
use App\Models\WorkLog\Entry;
use App\Models\WorkLog\OpenEntry;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;
use App\Domain\Employee\Events\EmployeeStartedWorking;

final class WorkLogProjector implements Projector
{
    use ProjectsEvents;

    public function onEmployeeStartedWorking(EmployeeStartedWorking $event, string $aggregateUuid)
    {
        $employee = Employee::byUuid($aggregateUuid);
        
        $entry = new OpenEntry;
        $entry->uuid = $event->uuid;
        $entry->start = $event->carbon();
        $entry->started_by = $event->scanner_uuid;
        
        $employee->OpenEntries()->save($entry);
    }

    public function onEmployeeStoppedWorking(EmployeeStoppedWorking $event, string $aggregateUuid)
    {
        $employee = Employee::byUuid($aggregateUuid);
        
        $open = OpenEntry::byUuid($event->uuid);
        
        $entry = new Entry;
        $entry->uuid = $event->uuid;
        $entry->start = $open->start;
        $entry->end = $event->carbon();
        $entry->started_by = $open->started_by;
        $entry->ended_by = $event->scanner_uuid;
        
        $employee->Entries()->save($entry);
        $open->delete();
    }

    public function onEmployeeWorkedFor(EmployeeWorkedFor $event, string $aggregateUuid)
    {
        $employee = Employee::byUuid($aggregateUuid);
        
        $entry = Entry::byUuid($event->entry_uuid);
        $entry->worked_minutes += $event->minutes;
        $entry->save();
    }

    public function onStartingEventReplay()
    {
        OpenEntry::truncate();
        Entry::truncate();
    }
}
