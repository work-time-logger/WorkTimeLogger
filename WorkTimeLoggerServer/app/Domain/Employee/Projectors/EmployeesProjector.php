<?php

namespace App\Domain\Employee\Projectors;

use App\Domain\Employee\Events\CardWasRegistered;
use App\Domain\Employee\Events\CardWasUnregistered;
use App\Domain\Employee\Events\EmployeeCreated;
use App\Models\Card;
use App\Models\Employee;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class EmployeesProjector implements Projector
{
    use ProjectsEvents;

    public function onCardWasRegistered(CardWasRegistered $event, string $aggregateUuid)
    {
        $card = Card::firstOrNew([
            'identifier' => $event->identifier
        ]);
        
        $employee = Employee::byUuid($aggregateUuid)->IdCards()->save($card);
    }

    public function onCardWasUnregistered(CardWasUnregistered $event, string $aggregateUuid)
    {
        $card = Card::firstOrNew([
            'identifier' => $event->identifier
        ]);

        $card->delete();
    }

    public function onStartingEventReplay()
    {
        Employee::truncate();
    }
}
