<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlphaTest extends TestCase
{
    /**
     * Test route /alpha
     *
     * @return void
     */
    public function testRouteAlpha_should_return_a_view_alpha()
    {
        $response = $this->get('/alpha');
        $response->assertSee('Alpha');
        $response->assertDontSee('Beta');
    }

    /**
     * Test route /alpha
     *
     * @return void
     */
    public function testClickLink_should_redirect_user_to_the_beta_page_when_user_clicks_the_next_link()
    {
        $response = $this->get('/api/demo');
        $response->assertjson(['success' => false]);
        
    }
}
