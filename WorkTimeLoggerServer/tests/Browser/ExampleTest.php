<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $user = factory(User::class)->create();
        
        $this->browse(/**
         * @param Browser $browser
         */ function (Browser $browser) use ($user) {
            $browser->visit('/')
                    ->assertSee('Laravel');
            
            $browser->clickLink('Login')
                ->waitForText('Password')
                ->assertSee('E-Mail Address')
                ->assertSee('Password');
            
            $browser->type('email', $user->email)
                ->type('password', 'password')
                ->press('Login')
                ->assertSee('You are logged in!');
        });
    }
}
