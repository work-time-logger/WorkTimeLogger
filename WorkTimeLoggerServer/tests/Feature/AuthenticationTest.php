<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    public function testHardwareApiUnauthenticatedResponseForMessagePackAcceptType()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/msgpack',
        ])->get('/hw/ping');

        $response
            ->assertStatus(401)
            ->assertExactMessagePack([
                'message' => "Unauthenticated."
            ]);
    }
    
    public function testHardwareApiUnauthenticatedResponseForJsonAcceptType()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/hw/ping');

        $response
            ->assertStatus(401)
            ->assertExactJson([
                'message' => "Unauthenticated."
            ]);
    }

    public function testHardwareApiUnauthenticatedResponseForBrowserAcceptType()
    {
        $response = $this->withHeaders([
            'Accept' => 'text/html',
        ])->get('/hw/ping');

        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }
}
