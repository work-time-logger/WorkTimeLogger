<?php

namespace Tests\Unit;

use App\Domain\Employee\EmployeeAgregate;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeAgregateTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    public function testEmployeeCreation()
    {
        $newUuid = Str::uuid();

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        
        EmployeeAgregate::retrieve($newUuid)
            ->createEmployee($firstName, $lastName)
            ->persist();

        $this->assertDatabaseHas('employees', [
            'uuid' => $newUuid,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
    }
}
