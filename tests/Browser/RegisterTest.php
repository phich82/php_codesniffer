<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;
    
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'phich82')
                    ->type('email', 'phich82@gmail.com')
                    ->type('password', '123456')
                    ->type('password_confirmation', '123456')
                    ->press('Register')
                    ->assertPathIs('/home')
                    ->assertSee('You are logged in!');
        });
    }
}
