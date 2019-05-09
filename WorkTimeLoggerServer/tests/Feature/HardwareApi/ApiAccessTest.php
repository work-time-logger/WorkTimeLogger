<?php

namespace Tests\Feature\HardwareApi;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAccessTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $hw = factory(\App\Models\HardwareScanner::class)->states('active')->create();
        
        $response = $this->get('/hw/ping', [
            'Authorization' => 'Bearer '.$hw->api_token
        ]);

        $response->assertStatus(200);
    }
}
