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

Route::get('setLocale/{locale}', "LocaleController@setLocale")->name('setLocale');

Route::get('user/profile/edit','ProfileController@edit')->middleware('auth')->name('ProfileEdition');
Route::patch('user/profile/edit', 'ProfileController@update')->middleware('auth')->name('ProfileUpdate');

Route::middleware(['verified'])->group(function () {
    
    // List of friend-related routes
    Route::group(['prefix'=>'friends'], function() {
        Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
            Route::put('add/{user}', 'AjaxFriendsController@addFriend');
            Route::delete('delete/{user}', 'AjaxFriendsController@deleteFriend');
            Route::patch('accept/{user}','AjaxFriendsController@acceptFriend');
            Route::delete('deny/{user}','AjaxFriendsController@denyFriend');
        });
    });

});

Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
    Route::get('tag/autocompleteHobby', 'AjaxTagsController@autocompleteHobby');
    Route::get('tag/autocompleteCity', 'AjaxTagsController@autocompleteCity');
    Route::get('tag/autocompleteUser', 'AjaxTagsController@autocompleteUser');
    Route::put('tag/addNew', 'AjaxTagsController@addNew');
    Route::delete('tag/deleteTag', 'AjaxTagsController@deleteTag');
});


Route::prefix('user')->group(function(){
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('home/post/{post}', 'HomeController@viewPost')->name('viewPost');
    Route::put('report', "HomeController@report")->name('reportUser');
    Route::delete('deleteNotifications', 'HomeController@deleteNotifications')->name('deleteNotifications');

    Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
        Route::get('getPost/{post}', 'HomeController@getPost')->name('ajaxGetPost');
        Route::post('newPost', 'HomeController@newPost')->name('ajaxNewPost');
        Route::post('editPost', 'HomeController@editPost')->name('ajaxEditPost');  
        Route::delete('deletePost', 'HomeController@deletePost')->name('ajaxDeletePost');
        Route::patch('likePost', 'HomeController@likePost')->name('ajaxLikePost');

        Route::get('getComments/{post}', 'CommentController@getComments')->name('ajaxGetComments');
        Route::get('getReplies/{comment}', 'CommentController@getReplies')->name('ajaxGetReplies');
        Route::get('getMorePosts', 'HomeController@getMorePosts')->name('ajaxGetPosts');
        Route::put('newComment', 'CommentController@newComment')->name('ajaxNewComment');
        Route::patch('editComment', 'CommentController@editComment')->name('ajaxEditComment');
        Route::patch('likeComment', 'CommentController@likeComment')->name('ajaxLikeComment');
        Route::delete('deleteComment', 'CommentController@deleteComment')->name('ajaxDeleteComment');

        Route::post('checkUser', 'HomeController@checkUser')->name('ajaxCheckUser');
        Route::get('getTaggedUsers/{post}', 'HomeController@getTagged')->name('ajaxGetTagged');
        Route::get('getCommentTaggedUsers/{comment}', 'CommentController@getTagged')->name('ajaxGetCommentTagged');

        Route::patch('likeUser', 'HomeController@likeUser')->name('ajaxLikeUser');
    });

    Route::get('profile', 'ProfileController@index')->middleware('auth')->name('ProfileView');
    Route::get('profile/{user}','ProfileController@visit')->name('ProfileOtherView');
    
    Route::get('profile/ajax/fetchContent', 'ProfileController@fetchContent')->name('ProfileFetchContent');
    Route::get('profile/ajax/searchFriends', 'ProfileController@searchFriends')->name('ProfileSearchFriends');

    Route::get('settings', 'SettingsController@index')->middleware('auth')->name('SettingsPage');
    Route::patch('settings', 'SettingsController@updateSettings')->middleware('auth')->name('SettingsUpdate');

    Route::get('contactAdministration', 'ContactController@index')->middleware('auth')->name('ContactPage');
    Route::post('contactAdministration', 'ContactController@sendMail')->middleware('auth')->name('ContactSendMail');

    Route::patch('readNotifications', 'ProfileController@readNotifications')->middleware('auth')->name('readNotifications');
});

Route::prefix('admin')->group(function(){
    Route::middleware(['verified'])->group(function () {
        Route::get('home', 'AdminController@index')->middleware('verified')->name('adminHome');

        Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
            Route::get('tab', 'AdminController@getTabContent')->name('adminAjaxTab');
            Route::patch('ticket','AdminController@resolveTicket')->name('adminAjaxTicket');
            Route::patch('list','AdminController@resolveListRequest')->name('adminAjaxList');
            Route::post('wideInfo', 'AdminController@wideInfo')->name('adminWideInfo');

            Route::get('pagiContent', 'AdminController@getPagi')->name('adminPagi');
            Route::get('searchList', 'AdminController@searchList')->name('adminSearch');
        });

    });
    
});

Route::get('message', 'MessageController@index')->name('message.app');
Route::get('message/{user}', 'MessageController@chatHistory')->name('message.read');
Route::delete('message/{id}', 'MessageController@deleteConversation')->name('conversation.delete');
Route::patch('message/{id}', 'MessageController@blockConversation')->name('conversation.block');


Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
   Route::get('message/getMore/{pagi}','AjaxMessageController@pagiConversations')->name('message.pagi');
   Route::get('message/get/{id}','AjaxMessageController@ajaxGetMessage')->name('message.get');
   Route::post('message/send', 'AjaxMessageController@ajaxSendMessage')->name('message.new');
   Route::delete('message/delete/{id}', 'AjaxMessageController@ajaxDeleteMessage')->name('message.delete');
   Route::patch('message/seen/{id}', 'AjaxMessageController@ajaxSeenMessage')->name('message.seen');

   Route::get('message/searchConvo', "AjaxMessageController@searchConvo")->name('message.search');
});


Route::get('searcher', 'SearchController@index')->name('searcher');

Route::prefix('culture')->group(function(){
    Route::get('culture', 'CultureController@index')->name('culture.mainPage');
    Route::get('{id}', "CultureController@item")->name('culture.read');
});


