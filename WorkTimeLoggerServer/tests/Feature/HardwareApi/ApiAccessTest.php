<?php

namespace Tests\Feature\HardwareApi;

use App\Domain\Scanner\ScannerAggregate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Scanner;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiAccessTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testPingingEndpointWithoutAuthorizationToken()
    {
        $response = $this->get('/hw/ping', [
                'Accept' => 'application/msgpack',
        ]);

        $response
            ->assertStatus(401);
    }

    public function testPingingEndpointWithInvalidAuthorizationToken()
    {
        $response = $this->get('/hw/ping', [
            'Accept' => 'application/msgpack',
            'Authorization' => 'Bearer '.Str::random()
        ]);

        $response
            ->assertStatus(401);
    }
    
    public function testPingingEndpointAsAuthenticatedUserUsingJson()
    {
        $scanner = $this->getNewScanner();
        
        $response = $this->get('/hw/ping', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$scanner->api_token
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'uuid' => $scanner->uuid,
                    'name' => $scanner->name,
                    'is_active' => $scanner->is_active,
                ]
            ]);
    }
    
    public function testPingingEndpointAsAuthenticatedUserUsingMessagePack()
    {
        $scanner = $this->getNewScanner();
        
        $response = $this->get('/hw/ping', [
            'Accept' => 'application/msgpack',
            'Authorization' => 'Bearer '.$scanner->api_token
        ]);

        $response
            ->assertStatus(200)
            ->assertExactMessagePack([
                'data' => [
                    'uuid' => $scanner->uuid,
                    'name' => $scanner->name,
                    'is_active' => $scanner->is_active,
                ]
            ]);
    }

    /**
     * @return Scanner
     */
    protected function getNewScanner(): Scanner
    {
        $scanner_uuid = Str::uuid();

        ScannerAggregate::retrieve($scanner_uuid)
            ->createScanner($this->faker->name)
            ->regenerateApiToken()
            ->enable()
            ->persist();

        return Scanner::byUuid($scanner_uuid);
    }
}
