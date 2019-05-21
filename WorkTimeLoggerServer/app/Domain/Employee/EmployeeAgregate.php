<?php

namespace App\Domain\Employee;

use App\Domain\Employee\Events\EmployeeCreated;
use App\Domain\Employee\Events\EmployeeStartedWorking;
use App\Domain\Employee\Events\EmployeeStoppedWorking;
use App\Domain\Employee\Events\EmployeeWorkedFor;
use App\Domain\Employee\Exceptions\CouldNotStopWorking;
use App\Domain\Employee\Exceptions\CouldNotStartWorking;
use Spatie\EventProjector\AggregateRoot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

final class EmployeeAgregate extends AggregateRoot
{
    const OPEN_WORK_LOG_ENTRY_EXPIRATION_IN_HOURS = 12;
    
    /**
     * @var array|Carbon[]
     */
    private $workLog = [];

    public function createEmployee(string $first_name, string $last_name)
    {
        $this->recordThat(new EmployeeCreated($first_name, $last_name));

        return $this;
    }

    public function startWork(string $uuid = null, Carbon $time = null)
    {
        $valid_entries = collect($this->workLog)->filter(function (Carbon $entry_time) use ($time) {
            return $entry_time->diffInHours($time, true) < self::OPEN_WORK_LOG_ENTRY_EXPIRATION_IN_HOURS;
        });

        if($valid_entries->count())
            throw CouldNotStartWorking::validEntryAlreadyExist();
        
        $this->recordThat(new EmployeeStartedWorking($uuid ?? Str::uuid(), $time ?? now()));

        return $this;
    }

    public function applyEmployeeStartedWorking(EmployeeStartedWorking $event)
    {
        $this->workLog[$event->uuid] = $event->carbon();
    }

    public function stopWork(string $uuid, Carbon $time = null)
    {
        $time = $time ?? now();

        if(!isset($this->workLog[$uuid]))
            throw CouldNotStopWorking::logDoesntExist();

        if($this->workLog[$uuid] > $time)
            throw CouldNotStopWorking::startIsInFuture();

        if($this->workLog[$uuid]->diffInHours($time, true) >= self::OPEN_WORK_LOG_ENTRY_EXPIRATION_IN_HOURS)
            throw CouldNotStopWorking::logExpired();

        $start_time = $this->workLog[$uuid];

        $this->recordThat(new EmployeeStoppedWorking($uuid, $time));
        
        $this->recordThat(new EmployeeWorkedFor($uuid, $start_time->format('Y-m-d'), $start_time->diffInMinutes($time)));

        return $this;
    }

    public function applyEmployeeStoppedWorking(EmployeeStoppedWorking $event)
    {
        unset($this->workLog[$event->uuid]);
    }
}
