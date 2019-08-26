<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_adults_can_create_an_account()
    {
        $this->post('/register', [
            'name' => 'Boi',
            'birth_year' => '2000',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);
        
        $this->assertCount(1, $users = User::all());
        $this->assertAuthenticatedAs($user = $users->first());
        $this->assertEquals('Boi', $user->name);
        $this->assertEquals('2000', $user->birth_year);
    }

    /** @test */
    public function test_minors_cannot_create_an_account()
    {
        $response = $this->post('/register', [
            'name' => 'Boi',
            'birth_year' => '2010',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);
        
        $this->assertCount(0, $users = User::all());
        $response->assertSessionHasErrorsIn('birth_year');
        $this->assertGuest();
    }
    
}
