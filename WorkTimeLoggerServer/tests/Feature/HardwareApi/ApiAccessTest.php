<?php

namespace Tests\Feature\HardwareApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\HardwareScanner;
use Tests\TestCase;

class ApiAccessTest extends TestCase
{
    use RefreshDatabase;

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
        $scanner = factory(HardwareScanner::class)->make();
        
        $response = $this->get('/hw/ping', [
            'Accept' => 'application/msgpack',
            'Authorization' => 'Bearer '.$scanner->api_token
        ]);

        $response
            ->assertStatus(401);
    }
    
    public function testPingingEndpointAsAuthenticatedUserUsingJson()
    {
        $scanner = factory(HardwareScanner::class)->states('inactive')->create();
        
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
        $scanner = factory(HardwareScanner::class)->create();
        
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
}
