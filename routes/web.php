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

Route::get('/', 'Auth\RegisterController@showRegistrationForm');

Auth::routes();

Route::get('searcher', 'SearchController@index')->name('searcher');


Route::middleware(['auth'])->group(function () {
    Route::get('profile', 'ProfileController@index')->middleware('auth')->name('ProfileView');
    Route::patch('profile', 'ProfileController@update')->middleware('auth');
    Route::get('profile/edit','ProfileController@edit')->middleware('auth')->name('ProfileEdition');
});
Route::get('profile/{user}','ProfileController@visit');

Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
    Route::get('tag/autocompleteHobby', 'AjaxTagsController@autocompleteHobby');
    Route::get('tag/autocompleteCity', 'AjaxTagsController@autocompleteCity');
    Route::put('tag/addNew', 'AjaxTagsController@addNew');
    Route::delete('tag/deleteTag', 'AjaxTagsController@deleteTag');
});


Route::prefix('user')->group(function(){
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('talk', 'HomeController@chat')->name('chat');
});

Route::get('message', 'MessageController@index')->name('message.app');
Route::get('message/{name}', 'MessageController@chatHistory')->name('message.read');
Route::delete('message/{id}', 'MessageController@deleteConversation')->name('conversation.delete');
Route::patch('message/{id}', 'MessageController@blockConversation')->name('conversation.block');


Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
   Route::get('message/getMore/{pagi}','AjaxMessageController@pagiConversations')->name('message.pagi');
   Route::get('message/get/{id}','AjaxMessageController@ajaxGetMessage')->name('message.get');
   Route::post('message/send', 'AjaxMessageController@ajaxSendMessage')->name('message.new');
   Route::delete('message/delete/{id}', 'AjaxMessageController@ajaxDeleteMessage')->name('message.delete');
   Route::patch('message/seen/{id}', 'AjaxMessageController@ajaxSeenMessage')->name('message.seen');
});

