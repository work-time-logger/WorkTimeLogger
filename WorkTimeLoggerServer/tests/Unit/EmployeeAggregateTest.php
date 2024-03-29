<?php

namespace Tests\Unit;

use App\Domain\Employee\EmployeeAggregate;
use App\Domain\Employee\Exceptions\CouldNotCreateEmployee;
use App\Domain\Employee\Exceptions\CouldNotStartWorking;
use App\Domain\Employee\Exceptions\CouldNotStopWorking;
use App\Domain\Scanner\ScannerAggregate;
use App\Models\Employee;
use App\Models\Scanner;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeAggregateTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    public function testEmployeeCreation()
    {
        $employee_uuid = Str::uuid();

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        
        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($firstName, $lastName)
            ->persist();

        $this->assertDatabaseHas('employees', [
            'uuid' => $employee_uuid,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
    }
    
    public function testEmployeeCreationOfAlreadyCreatedUser()
    {
        $employee_uuid = Str::uuid();

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        
        $aggreggate = EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($firstName, $lastName)
            ->persist();
        
        $this->expectException(CouldNotCreateEmployee::class);
        $this->expectExceptionMessage("Employee with provided ID already exists.");

        $aggreggate->createEmployee($firstName, $lastName);
    }
    
    public function testEmployeeOpeningWorkLog()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());

        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time)
            ->persist();

        $this->assertDatabaseHas('open_entries', [
            'employee_uuid' => $employee_uuid,
            'uuid' => $entry_uuid,
            'start' => $start_time->format('Y-m-d H:i:s'),
            'started_by' => null,
        ]);
    }
    
    public function testEmployeeOpeningWorkLogWithSpecifiedScanner()
    {
        $scanner_uuid = Str::uuid();
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());
        
        ScannerAggregate::retrieve($scanner_uuid)
            ->createScanner($this->faker->name)
            ->persist();

        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time, Scanner::byUuid($scanner_uuid))
            ->persist();

        $this->assertDatabaseHas('open_entries', [
            'employee_uuid' => $employee_uuid,
            'uuid' => $entry_uuid,
            'start' => $start_time->format('Y-m-d H:i:s'),
            'started_by' => $scanner_uuid,
        ]);
    }
    
    public function testNonExistingEmployeeOpeningWorkLog()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());

        $aggregate = EmployeeAggregate::retrieve($employee_uuid);
        
        $this->expectException(CouldNotStartWorking::class);
        $this->expectExceptionMessage("Employee doesn't exist.");
        
        $aggregate->startWork($entry_uuid, $start_time);
    }
    
    public function testEmployeeClosingWorkLog()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp())->startOfMinute();
        $worked_minutes = 60 * 8;
        $end_time = $start_time->copy()->addMinutes($worked_minutes);

        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time)
            ->stopWork($entry_uuid, $end_time)
            ->persist();
        
        $this->assertDatabaseHas('entries', [
            'employee_uuid' => $employee_uuid,
            'uuid' => $entry_uuid,
            'start' => $start_time->format('Y-m-d H:i:s'),
            'end' => $end_time->format('Y-m-d H:i:s'),
            'worked_minutes' => $worked_minutes,
            'started_by' => null,
            'ended_by' => null,
        ]);

        $this->assertDatabaseMissing('open_entries', [
            'uuid' => $entry_uuid
        ]);
    }
    
    public function testEmployeeClosingWorkLogWithSpecifiedScanner()
    {
        $scanner_uuid = Str::uuid();
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp())->startOfMinute();
        $worked_minutes = 60 * 8;
        $end_time = $start_time->copy()->addMinutes($worked_minutes);
        
        ScannerAggregate::retrieve($scanner_uuid)
            ->createScanner($this->faker->name)
            ->persist();

        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time)
            ->stopWork($entry_uuid, $end_time, Scanner::byUuid($scanner_uuid))
            ->persist();
        
        $this->assertDatabaseHas('entries', [
            'employee_uuid' => $employee_uuid,
            'uuid' => $entry_uuid,
            'start' => $start_time->format('Y-m-d H:i:s'),
            'end' => $end_time->format('Y-m-d H:i:s'),
            'worked_minutes' => $worked_minutes,
            'started_by' => null,
            'ended_by' => $scanner_uuid,
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

        $agregate = EmployeeAggregate::retrieve($employee_uuid)
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

        $agregate = EmployeeAggregate::retrieve($employee_uuid)
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

        $agregate = EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time);

        $this->expectException(CouldNotStopWorking::class);
        $this->expectExceptionMessage("End date cannot be before start date.");
        
        $agregate->stopWork($entry_uuid, $end_time);
    }

    public function testEmployeeOpeningWorkLogWhileHavingAlreadyOpenedValidEntry()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $second_entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());
        $second_start_time = $start_time->copy()->addHour();

        $agregate = EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time);
        
        $this->expectException(CouldNotStartWorking::class);
        $this->expectExceptionMessage("There is valid, already started entry.");
        
        $agregate->startWork($second_entry_uuid, $second_start_time);
    }

    public function testEmployeeOpeningWorkLogWhileHavingAlreadyOpenedInvalidEntry()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $second_entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp());
        $second_start_time = $start_time->copy()->addDay();

        $agregate = EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time)
            ->startWork($second_entry_uuid, $second_start_time)
            ->persist();

        $this->assertDatabaseHas('open_entries', [
            'employee_uuid' => $employee_uuid,
            'uuid' => $entry_uuid,
            'start' => $start_time->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('open_entries', [
            'employee_uuid' => $employee_uuid,
            'uuid' => $second_entry_uuid,
            'start' => $second_start_time->format('Y-m-d H:i:s'),
        ]);
    }

    public function testEmployeeCountingSummary()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp())->setHour(10);
        $worked_minutes = 60 * 3;
        $end_time = $start_time->copy()->addMinutes($worked_minutes);

        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time)
            ->stopWork($entry_uuid, $end_time)
            ->persist();

        $this->assertDatabaseHas('daily_summaries', [
            'employee_uuid' => $employee_uuid,
            'day' => $start_time->format('Y-m-d'),
            'worked_minutes' => $worked_minutes
        ]);
    }

    public function testEmployeeCountingSummaryOfMultipleEntries()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp())->setHour(10);
        $worked_minutes = 60 * 3;
        $end_time = $start_time->copy()->addMinutes($worked_minutes);

        $second_entry_uuid = Str::uuid();
        $second_start_time = $end_time->copy()->addMinutes(rand(60,120));
        $second_worked_minutes = 60 * 5;
        $second_end_time = $second_start_time->copy()->addMinutes($second_worked_minutes);

        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time)
            ->stopWork($entry_uuid, $end_time)
            ->startWork($second_entry_uuid, $second_start_time)
            ->stopWork($second_entry_uuid, $second_end_time)
            ->persist();

        $this->assertDatabaseHas('daily_summaries', [
            'employee_uuid' => $employee_uuid,
            'day' => $start_time->format('Y-m-d'),
            'worked_minutes' => $worked_minutes + $second_worked_minutes
        ]);
    }

    public function testEmployeeCountingSummaryOverNight()
    {
        $employee_uuid = Str::uuid();
        $entry_uuid = Str::uuid();
        $start_time = Carbon::createFromTimestamp($this->faker->dateTimeBetween('-1 month')->getTimestamp())->setHour(22)->startOfHour();
        $worked_minutes = 60 * 5;
        $end_time = $start_time->copy()->addMinutes($worked_minutes);

        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($this->faker->firstName, $this->faker->lastName)
            ->startWork($entry_uuid, $start_time)
            ->stopWork($entry_uuid, $end_time)
            ->persist();

        $this->assertDatabaseHas('daily_summaries', [
            'employee_uuid' => $employee_uuid,
            'day' => $start_time->format('Y-m-d'),
            'worked_minutes' => 2*60
        ]);

        $this->assertDatabaseHas('daily_summaries', [
            'employee_uuid' => $employee_uuid,
            'day' => $end_time->format('Y-m-d'),
            'worked_minutes' => 3*60
        ]);

        $this->assertDatabaseHas('entries', [
            'employee_uuid' => $employee_uuid,
            'uuid' => $entry_uuid,
            'start' => $start_time->format('Y-m-d H:i:s'),
            'end' => $end_time->format('Y-m-d H:i:s'),
            'worked_minutes' => $worked_minutes
        ]);
    }

    public function testCardRegistration()
    {
        $employee_uuid = Str::uuid();
        $card_identifier = Str::random();

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($firstName, $lastName)
            ->registerCard($card_identifier)
            ->persist();

        $this->assertDatabaseHas('cards', [
            'employee_uuid' => $employee_uuid,
            'identifier' => $card_identifier,
        ]);
    }

    public function testCardUnregistration()
    {
        $employee_uuid = Str::uuid();
        $card_identifier = Str::random();

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        EmployeeAggregate::retrieve($employee_uuid)
            ->createEmployee($firstName, $lastName)
            ->registerCard($card_identifier)
            ->unregisterCard($card_identifier)
            ->persist();

        $this->assertDatabaseMissing('cards', [
            'identifier' => $card_identifier,
        ]);
    }
}
