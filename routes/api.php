<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::apiResources([
    'users'=>'Api\UserController',
    'tag'=>'Api\TagController',
]);
Route::get('profile','Api\UserController@profile');
Route::put('profile','Api\UserController@updateProfile');
Route::get('findUser','Api\UserController@findUser');
Route::get('changeImageToWebp','Api\ArtisanController@changeImageToWebp');
Route::resource('category','Api\CategoryController');

Route::resource('posts','Api\PostController');
Route::get('allPosts','Api\BlogController@allPosts');
Route::post('post/update','Api\PostController@postUpdate');

Route::get('9724prashant','Api\ArtisanController@setup');

/**
 * ads api for admin panel
 **/
Route::get('ads','Api\AdsController@index');
Route::post('ads','Api\AdsController@store');
Route::post('ads/status','Api\AdsController@status');
Route::get('ads/delete/{id}','Api\AdsController@delete');

//commets

Route::post('addComment','Api\CommentController@addComment');
Route::post('getComments','Api\CommentController@getComments');

Route::middleware(['auth:api'])->group(function () {
    Route::get('getAllComments','Api\CommentController@getAllComments');
    Route::post('getSingleComment','Api\CommentController@getSingleComment');
    Route::post('updateComment','Api\CommentController@updateComment');
});

Route::post('deleteComment','Api\CommentController@getAllComments');


/**
 * this APIs for Blog Api
 */
//api for getting posts
Route::post('updatePostViewCounts','Api\BlogController@updatePostViewCounts');
Route::get('getPostByViews','Api\BlogController@getPostByViews');

Route::get('getAllPosts','Api\BlogController@getAllPosts');
Route::get('getCategories','Api\BlogController@getCategories');
Route::get('getRecentPosts','Api\BlogController@getRecentPosts');
Route::get('getMostReadedPosts','Api\BlogController@getMostReadedPosts');
Route::post('getRelatedPosts','Api\BlogController@getRelatedPosts');
Route::get('getFeaturedPosts','Api\BlogController@getFeaturedPosts');


Route::get('getTags','Api\BlogController@getTags');
Route::get('getPostsByCategory/{id}','Api\BlogController@getPostsByCategory');
Route::get('getSinglePost/{id}','Api\BlogController@getSinglePost');
Route::post('getSlugPost','Api\BlogController@getSlugPost');


Route::post('subscribe','Api\Blog\BlogController@saveSubscription');
Route::post('contactUs','Api\Blog\BlogController@contactUs');

Route::post('search','Api\Blog\BlogController@search');
Route::get('getRadomeAd','Api\Blog\BlogController@getRadomeAd');
Route::get('getTop2Post','Api\Blog\BlogController@getTop2Post');
//firebase
Route::post('uploadFirebaseToken','Api\FirebaseController@uploadFirebaseToken');

