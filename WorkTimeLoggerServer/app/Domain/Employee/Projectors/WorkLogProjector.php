<?php

namespace App\Domain\Employee\Projectors;

use App\Domain\Employee\Events\EmployeeStoppedWorking;
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
        $employee = Employee::uuid($aggregateUuid);
        
        $entry = new OpenEntry;
        $entry->uuid = $event->uuid;
        $entry->start = $event->carbon();
        
        $employee->OpenEntries()->save($entry);
    }

    public function onEmployeeStoppedWorking(EmployeeStoppedWorking $event, string $aggregateUuid)
    {
        $employee = Employee::uuid($aggregateUuid);
        
        $open = OpenEntry::uuid($event->uuid);
        
        $entry = new Entry;
        $entry->uuid = $event->uuid;
        $entry->start = $open->start;
        $entry->end = $event->carbon();
        $entry->worked_minutes = $entry->start->diffInMinutes($entry->end);
        
        $employee->Entries()->save($entry);
        $open->delete();
        
        $summary = $employee->DailySummaries()->firstOrNew([
            'day' => $open->start->format('Y-m-d')
        ]);
        $summary->worked_minutes += $entry->worked_minutes;
        $summary->save();
    }
}
