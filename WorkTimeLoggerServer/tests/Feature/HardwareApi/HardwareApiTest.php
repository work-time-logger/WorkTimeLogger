<?php

namespace Tests\Feature\HardwareApi;

use App\Domain\Employee\EmployeeAgregate;
use App\Models\Employee;
use App\Models\IdCard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\HardwareScanner;
use Illuminate\Support\Str;
use Tests\TestCase;

class HardwareApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    public function testQueryingForNonExistingCard()
    {
        $employee = $this->getNewEmployee();
        
        $scanner = factory(HardwareScanner::class)->create();
        
        $response = $this->get('/hw/card?id='.Str::random(8), [
            'Accept' => 'application/msgpack',
            'Authorization' => 'Bearer '.$scanner->api_token
        ]);

        $response
            ->assertStatus(404);
    }
    
    public function testQueryingForExistingNonUsedCard()
    {
        $employee = $this->getNewEmployee();
        $card = $this->getNewCardFor($employee);
        
        $scanner = factory(HardwareScanner::class)->create();
        
        $response = $this->get('/hw/card/'.$card->rfid_id, [
            'Accept' => 'application/msgpack',
            'Authorization' => 'Bearer '.$scanner->api_token
        ]);

        $response
            ->assertStatus(200)
            ->assertExactMessagePack([
                'employee' => $employee->uuid,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'worked_today' => 0,
                'open_entry' => null,
                'has_invalid_entries' => false,
            ]);
    }
    
    
    
    

    protected function getNewEmployee(): Employee
    {
        $employee_uuid = Str::uuid();

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        EmployeeAgregate::retrieve($employee_uuid)
            ->createEmployee($firstName, $lastName)
            ->persist();

        return Employee::uuid($employee_uuid);
    }

    /**
     * @param Employee $employee
     *
     * @return IdCard
     */
    protected function getNewCardFor(Employee $employee): IdCard
    {
        $card = new IdCard();
        $card->uuid = Str::uuid();
        $card->rfid_id = Str::random(16);
        $employee->IdCards()->save($card);
        return $card;
    }
}
