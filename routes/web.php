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


Auth::routes(['verify' => true]);
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('setLocale/{locale}', "LocaleController@setLocale")->name('setLocale');

Route::prefix('user/profile')->group(function(){
    Route::get('/edit','ProfileController@edit')->middleware('auth')->name('ProfileEdition');
    Route::patch('/edit', 'ProfileController@update')->middleware('auth')->name('ProfileUpdate');
    Route::delete('/delete', 'ProfileController@delete')->middleware('auth')->name('ProfileDelete');
});

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
    Route::get('tag/autocompleteCategory', 'AjaxTagsController@autocompleteCategory');
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

        Route::get('getCultComments/{culture_item}', 'CommentController@getCultComments')->name('ajaxGetCultComments');
        Route::get('getComments/{post}', 'CommentController@getComments')->name('ajaxGetComments');

        Route::get('getReplies/{comment}', 'CommentController@getReplies')->name('ajaxGetReplies');
        Route::get('getCultReplies/{culture_comment}', 'CommentController@getCultReplies')->name('ajaxGetCultReplies');

        Route::get('getMorePosts', 'HomeController@getMorePosts')->name('ajaxGetPosts');
        Route::put('newComment', 'CommentController@newComment')->name('ajaxNewComment');
        Route::patch('editComment', 'CommentController@editComment')->name('ajaxEditComment');
        Route::patch('likeComment', 'CommentController@likeComment')->name('ajaxLikeComment');
        Route::delete('deleteComment', 'CommentController@deleteComment')->name('ajaxDeleteComment');

        Route::post('checkUser', 'HomeController@checkUser')->name('ajaxCheckUser');
        Route::get('getTaggedUsers/{post}', 'HomeController@getTagged')->name('ajaxGetTagged');

        Route::get('getCommentTaggedUsers/{comment}', 'CommentController@getTagged')->name('ajaxGetCommentTagged');
        Route::get('getCultCommentTaggedUsers/{culture_comment}', 'CommentController@getCultTagged')->name('ajaxGetCultCommentTagged');

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
        Route::get('culture', 'AdminController@culture')->middleware('verified')->name('adminCulture');
        Route::get('blog', 'AdminController@blog')->middleware('verified')->name('adminBlog');

        Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
            Route::get('tab', 'AdminController@getTabContent')->name('adminAjaxTab');
            Route::patch('ticket','AdminController@resolveTicket')->name('adminAjaxTicket');
            Route::patch('list','AdminController@resolveListRequest')->name('adminAjaxList');
            Route::post('wideInfo', 'AdminController@wideInfo')->name('adminWideInfo');

            Route::put('newPartners','AdminController@newPartners')->name('adminAjaxPartners');

            Route::get('pagiContent', 'AdminController@getPagi')->name('adminPagi');
            Route::get('searchList', 'AdminController@searchList')->name('adminSearch');
        });

    });
    
});

Route::prefix('message')->group(function(){
    Route::get('/', 'MessageController@index')->name('message.app');
    Route::get('/{user}', 'MessageController@chatHistory')->name('message.read');
    Route::delete('/{id}', 'MessageController@deleteConversation')->name('conversation.delete');
    Route::patch('/{id}', 'MessageController@blockConversation')->name('conversation.block');
});
Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
   Route::get('message/getMore/{pagi}','AjaxMessageController@pagiConversations')->name('message.pagi');
   Route::get('message/get/{id}','AjaxMessageController@ajaxGetMessage')->name('message.get');
   Route::post('message/send', 'AjaxMessageController@ajaxSendMessage')->name('message.new');
   Route::delete('message/delete/{id}', 'AjaxMessageController@ajaxDeleteMessage')->name('message.delete');
   Route::patch('message/seen/{id}', 'AjaxMessageController@ajaxSeenMessage')->name('message.seen');

   Route::get('message/searchConvo', "AjaxMessageController@searchConvo")->name('message.search');
});

Route::prefix('culture')->group(function(){
    Route::get('/', 'CultureController@index')->name('culture.mainPage');
    Route::get('/search', 'CultureController@searchResults')->name('culture.searchResults');
    Route::put('/newCategory', 'CultureController@newCategory')->middleware('admin')->name('culture.newCategory');
    
    Route::get('/{cultureItem}', "CultureController@item")->name('culture.read');
    
    Route::put('/newItem', 'CultureController@newItem')->middleware('admin')->name('culture.newItem');
    
    Route::delete('/deleteItem', 'CultureController@deleteItem')->middleware('admin')->name('culture.deleteItem');

    Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function(){
        Route::get('getReview', 'CultureController@getReview')->name('ajaxGetReview');

        Route::patch('likeItem', 'HomeController@likeItem')->name('ajaxLikeItem');
    });
});

Route::get('searcher', 'SearchController@index')->name('searcher');

Route::get('partners', "CultureController@partners")->name('culture.partners');


Route::get('/', 'BlogController@index')->name('blog.mainPage');
Route::prefix('blog')->group(function(){
    Route::get('/{blogPost}', 'BlogController@post')->name('blog.post');
    Route::put('/newPost', 'BlogController@newPost')->name('blog.newPost');
    Route::delete('/deletePost', 'BlogController@deletePost')->name('blog.deletePost');
    Route::patch('/likePost', "BlogController@likePost");
});

