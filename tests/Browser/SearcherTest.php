<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Chrome;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KurwaTest extends DuskTestCase
{

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testSearching_by_age_will_return_all_records()
    {
        $users = factory(User::class,5)->create([
            'age' => 20,
        ]);

        $this->browse(function ($browser){
            $browser->visit('/searcher')
                    ->type('age-min', '18')
                    ->type('age-max', '25')
                    ->press('Szukaj')
                    ->assertPathIs('/searcher')
                    ->assertQueryStringHas('age-min','18')
                    ->assertQueryStringHas('age-max','25')
                    ->assertSeeIn('@search_results_header', 'Ilość wyników: 5');
        });

        foreach ($users as $user) {
            $user->delete();
        }
    }

    public function testSearching_out_of_bounds_will_return_error_message()
    {
        $users = factory(User::class,5)->create([
            'age' => 20,
        ]);

        $this->browse(function ($browser){
            $browser->visit('/searcher')
                    ->type('age-min', '25')
                    ->type('age-max', '50')
                    ->press('Szukaj')
                    ->assertPathIs('/searcher')
                    ->assertQueryStringHas('age-min','25')
                    ->assertQueryStringHas('age-max','50')
                    ->assertSeeIn('@search_results_header', 'Nie znaleziono użytkowników w podanych kryteriach');
        });
        
        foreach ($users as $user) {
            $user->delete();
        }
    }

    public function testSearching_by_partial_username_will_return_a_specified_user()
    {
        $user = factory(User::class)->create([
            'name' => 'xxx_Jackob_xxx',
        ]);

        $this->browse(function ($browser){
            $browser->visit('/searcher')
                    ->type('username', 'xxx')
                    ->press('Szukaj')
                    ->assertPathIs('/searcher')
                    ->assertQueryStringHas('username','xxx')
                    ->assertSeeIn('@search_results_box', 'xxx_Jackob_xxx');
        });
        
        $user->delete();
    }
}