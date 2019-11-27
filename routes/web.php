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

Auth::routes(['verify' => true]);

Route::get('logout', 'Auth\LoginController@logout');

Route::get('searcher', 'SearchController@index')->name('searcher');

Route::middleware(['verified'])->group(function () {
    Route::patch('profile', 'ProfileController@update');
    Route::get('profile/edit','ProfileController@edit')->name('ProfileEdition');
    Route::group(['prefix'=>'friends'], function() {
        // Ya need to be 'verified' to do stuff with friends
        Route::get('add/{user}','FriendsController@addFriend');
        Route::get('accept/{user}','FriendsController@acceptFriend');
        Route::get('deny/{user}','FriendsController@denyRequest');
});});

Route::get('profile', 'ProfileController@index')->middleware('auth')->name('ProfileView');
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
    Route::put('report', "HomeController@report")->name('reportUser');
    Route::patch('readNotifications', 'HomeController@readNotifications')->name('readNotifications');
    Route::delete('deleteNotifications', 'HomeController@deleteNotifications')->name('deleteNotifications');
    Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
        Route::get('getPost/{post}', 'HomeController@getPost')->name('ajaxGetPost');
        Route::post('newPost', 'HomeController@newPost')->name('ajaxNewPost');
        Route::post('editPost', 'HomeController@editPost')->name('ajaxEditPost');  
        Route::delete('deletePost', 'HomeController@deletePost')->name('ajaxDeletePost');
    });
});

Route::prefix('admin')->group(function(){
    Route::get('home', 'AdminController@index')->middleware('verified')->name('adminHome');

    Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
        Route::get('tab', 'AdminController@getTabContent')->middleware('verified')->name('adminAjaxTab');
        Route::patch('ticket','AdminController@resolveTicket')->middleware('verified')->name('adminAjaxTicket');
        Route::patch('list','AdminController@resolveListRequest')->middleware('verified')->name('adminAjaxList');
        Route::post('wideInfo', 'AdminController@wideInfo')->middleware('verified')->name('adminWideInfo');
    });
    
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

