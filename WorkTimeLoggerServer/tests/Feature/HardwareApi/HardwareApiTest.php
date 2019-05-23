<?php

namespace Tests\Feature\HardwareApi;

use App\Domain\Employee\EmployeeAgregate;
use App\Models\Employee;
use App\Models\IdCard;
use App\Models\WorkLog\OpenEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\HardwareScanner;
use Illuminate\Support\Carbon;
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
    
    public function testStartingWorkTime()
    {
        $employee = $this->getNewEmployee();
        $card = $this->getNewCardFor($employee);
        
        $scanner = factory(HardwareScanner::class)->create();
        
        $now = today()->setHour(10);
        Carbon::setTestNow($now);
        
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
        
        $response = $this->post('/hw/card/'.$card->rfid_id.'/start', [], [
            'Accept' => 'application/msgpack',
            'Authorization' => 'Bearer '.$scanner->api_token
        ]);


        $response->assertStatus(200)
            ->assertExactMessagePack([
                'entry' => $entry_id = OpenEntry::firstOrFail()->uuid,
                'start' => $now->format('Y-m-d H:i:s'),
            ]);
        
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
                'open_entry' => $entry_id,
                'has_invalid_entries' => false,
            ]);
        
    }
    
    public function testCantStartWorkWhenAlreadyWorking()
    {
        $now = today()->setHour(10);
        Carbon::setTestNow($now);
        
        $employee = $this->getNewEmployee();
        $employee->getAgregate()->startWork(Str::uuid(),$now->copy()->subHour())->persist();
        $card = $this->getNewCardFor($employee);
        
        $scanner = factory(HardwareScanner::class)->create();

        $response = $this->post('/hw/card/'.$card->rfid_id.'/start', [], [
            'Accept' => 'application/msgpack',
            'Authorization' => 'Bearer '.$scanner->api_token
        ]);
        
        $response->assertStatus(422)
            ->assertExactMessagePack([
                'message' => 'There is valie, already started entry.'
            ]);
    }
    
    public function testQueryingForExistingCardWithInvalidEntries()
    {
        $now = today()->setHour(10);
        Carbon::setTestNow($now);
        
        $employee = $this->getNewEmployee();
        $employee->getAgregate()->startWork(Str::uuid(),$now->copy()->subWeek())->persist();
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
                'has_invalid_entries' => true,
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
