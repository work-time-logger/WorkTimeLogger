<?php

namespace Tests\Unit;

use App\Domain\Employee\EmployeeAggregate;
use App\Domain\Employee\Exceptions\CouldNotCreateEmployee;
use App\Domain\Scanner\Exceptions\ScannerAlreadyExists;
use App\Domain\Scanner\ScannerAggregate;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScannerAggregateTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testScannerCreation()
    {
        $scanner_uuid = Str::uuid();

        $name = $this->faker->name;

        ScannerAggregate::retrieve($scanner_uuid)
            ->createScanner($name)
            ->persist();

        $this->assertDatabaseHas('scanners', [
            'uuid' => $scanner_uuid,
            'name' => $name,
            'api_token' => null,
            'is_active' => false,
        ]);
    }

    public function testScannerCreationOfAlreadyCreatedUser()
    {
        $scanner_uuid = Str::uuid();

        $name = $this->faker->name;

        $aggregate = ScannerAggregate::retrieve($scanner_uuid)
            ->createScanner($name)
            ->persist();

        $this->expectException(ScannerAlreadyExists::class);
        $this->expectExceptionMessage("Scanner already exist.");

        $aggregate->createScanner($name);
    }

    public function testApiTokenRegeneration()
    {
        $scanner_uuid = Str::uuid();
        $new_token = Str::random();

        $name = $this->faker->name;

        ScannerAggregate::retrieve($scanner_uuid)
            ->createScanner($name)
            ->regenerateApiToken($new_token)
            ->persist();

        $this->assertDatabaseHas('scanners', [
            'uuid' => $scanner_uuid,
            'name' => $name,
            'api_token' => $new_token,
            'is_active' => false,
        ]);
    }

    public function testEnablingScanner()
    {
        $scanner_uuid = Str::uuid();

        $name = $this->faker->name;

        ScannerAggregate::retrieve($scanner_uuid)
            ->createScanner($name)
            ->enable()
            ->persist();

        $this->assertDatabaseHas('scanners', [
            'uuid' => $scanner_uuid,
            'name' => $name,
            'api_token' => null,
            'is_active' => true,
        ]);
    }

    public function testDisablingScanner()
    {
        $scanner_uuid = Str::uuid();

        $name = $this->faker->name;

        ScannerAggregate::retrieve($scanner_uuid)
            ->createScanner($name)
            ->enable()
            ->disable()
            ->persist();

        $this->assertDatabaseHas('scanners', [
            'uuid' => $scanner_uuid,
            'name' => $name,
            'api_token' => null,
            'is_active' => false,
        ]);
    }
}
