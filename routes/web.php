<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('searcher');
    }
    return view('welcome');
});

Auth::routes();

Route::get('searcher', 'SearchController@index')->name('searcher');

Route::prefix('user')->group(function(){
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('talk', 'HomeController@chat')->name('chat');
});

Route::get('notification', function(){
    return view('notification-test');
});

Route::get('message/{id}', 'MessageController@chatHistory')->name('message.read');
Route::get('message', 'MessageController@index')->name('message.app');

Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
   Route::post('message/send', 'MessageController@ajaxSendMessage')->name('message.new');
   Route::delete('message/delete/{id}', 'MessageController@ajaxDeleteMessage')->name('message.delete');
   Route::patch('message/seen/{id}', 'MessageController@ajaxSeenMessage')->name('message.seen');
});

Route::get('test', function () {
    event(new App\Events\MessageSent('Someone'));
    return "Event has been sent!";
});

