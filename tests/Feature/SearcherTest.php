<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearcherTest extends TestCase
{
    use RefreshDatabase;

    public function testGuests_can_see_the_searcher()
    {
        $response = $this->get('/searcher');

        $response->assertOk();
        $response->assertViewIs('searcher');
        $this->assertGuest();
    }

    public function testUsers_can_see_the_searcher()
    {

        $user = factory(User::class)->create();


        $response = $this->actingAs($user)->get('/searcher');

        $response->assertOk();
        $response->assertViewIs('searcher');
        $this->assertAuthenticated();
    }
    
}
