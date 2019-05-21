<?php

namespace Tests\Unit;

use App\Domain\Employee\EmployeeAgregate;
use App\Domain\Employee\Exceptions\CouldNotStopWorking;
use App\Models\Employee;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeAgregateTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    public function testEmployeeCreation()
    {
        $employee_uuid = Str::uuid();

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        
        EmployeeAgregate::retrieve($employee_uuid)
            ->createEmployee($firstName, $lastName)
            ->persist();

        $this->assertDatabaseHas('employees', [
            'uuid' => $employee_uuid,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
    }
    
    public function testEmployeeOpeningWorkLog()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());

        EmployeeAgregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time)
            ->persist();
        
        $employee = Employee::uuid($employee_uuid);

        $this->assertDatabaseHas('open_entries', [
            'employee_id' => $employee->id,
            'uuid' => $entry_uuid,
            'start' => $start_time->format('Y-m-d H:i:s'),
        ]);
    }
    
    public function testEmployeeClosingWorkLog()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());
        $worked_minutes = 60 * 8;
        $end_time = $start_time->copy()->addMinutes($worked_minutes);

        EmployeeAgregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time)
            ->stopWork($entry_uuid, $end_time)
            ->persist();
        
        $employee = Employee::uuid($employee_uuid);

        $this->assertDatabaseHas('entries', [
            'employee_id' => $employee->id,
            'uuid' => $entry_uuid,
            'start' => $start_time->format('Y-m-d H:i:s'),
            'end' => $end_time->format('Y-m-d H:i:s'),
            'worked_minutes' => $worked_minutes
        ]);

        $this->assertDatabaseMissing('open_entries', [
            'uuid' => $entry_uuid
        ]);
    }
    
    public function testEmployeeClosingNonExistingWorkLog()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());
        $end_time = $start_time->copy()->addHour();

        $agregate = EmployeeAgregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName);

        $this->expectException(CouldNotStopWorking::class);
        $this->expectExceptionMessage("Could not stop working, because work log entry is already stopped or doesn't exist.");
        
        $agregate->stopWork($entry_uuid, $end_time);
    }
    
    public function testEmployeeClosingExpiredWorkLog()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());
        $end_time = $start_time->copy()->addWeek();

        $agregate = EmployeeAgregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time);

        $this->expectException(CouldNotStopWorking::class);
        $this->expectExceptionMessage("Could not stop working, because work log entry you wanted to stop has expired.");
        
        $agregate->stopWork($entry_uuid, $end_time);
    }
    
    public function testEmployeeClosingWorkLogWithTimeInPast()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());
        $end_time = $start_time->copy()->subHour();

        $agregate = EmployeeAgregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time);

        $this->expectException(CouldNotStopWorking::class);
        $this->expectExceptionMessage("End date cannot be before start date.");
        
        $agregate->stopWork($entry_uuid, $end_time);
    }
}