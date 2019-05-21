<?php

namespace Tests\Feature\HardwareApi;

use App\Models\HardwareScanner;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAccessTest extends TestCase
{
    use RefreshDatabase;
    
    public function testPingingEndpointAsAuthenticatedUserUsingJson()
    {
        $this->withoutExceptionHandling();
        
        $scanner = factory(HardwareScanner::class)->states('active')->create();
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/hw/ping', [
            'Authorization' => 'Bearer '.$scanner->api_token
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'uuid' => $scanner->uuid,
                'name' => $scanner->name,
                'is_active' => $scanner->is_active,
            ]);
    }
}
