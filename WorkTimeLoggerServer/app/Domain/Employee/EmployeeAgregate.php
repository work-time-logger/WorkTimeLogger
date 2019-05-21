<?php

namespace App\Domain\Employee;

use App\Domain\Employee\Events\EmployeeCreated;
use App\Domain\Employee\Events\EmployeeStartedWorking;
use App\Domain\Employee\Events\EmployeeStoppedWorking;
use App\Domain\Employee\Exceptions\CouldNotStopWorking;
use Spatie\EventProjector\AggregateRoot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

final class EmployeeAgregate extends AggregateRoot
{
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

        if($this->workLog[$uuid]->diffInHours($time, true) >= 12)
            throw CouldNotStopWorking::logExpired();

        $this->recordThat(new EmployeeStoppedWorking($uuid, $time));

        return $this;
    }

    public function applyEmployeeStoppedWorking(EmployeeStoppedWorking $event)
    {
        unset($this->workLog[$event->uuid]);
    }
}
