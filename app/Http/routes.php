<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => ['web']], function () {
    Route::get('/', [
        'uses' => 'HomeController@getHome',
        'as'   => 'home',
    ]);

    Route::get('/auth', [
        'uses' => 'AuthController@getAuth',
        'as'   => 'auth.index',
    ]);

    Route::post('/contact-us', [
        'uses' => 'ContactUsController@postForm',
        'as'   => 'contact',
    ]);
    Route::get('privacy-policy', [
        'uses' => 'PrivacyController@getPrivacy',
        'as'   => 'privacy',
    ]);
});

Route::group(['middleware' => ['slack_commands_auth']], function() {

    Route::post('/praise', [
        'uses' => 'PraiseController@postPraise',
    ]);
    Route::post('praise-list-yesterday', [
        'uses' => 'PraiseController@postPraiseYesterday',
    ]);
    Route::post('praise-list', [
        'uses' => 'PraiseController@postPraiseToday',
    ]);
    Route::post('praise-list-last-week', [
        'uses' => 'PraiseController@postLastWeek',
    ]);
    Route::post('praise-list-last-month', [
        'uses' => 'PraiseController@postLastMonth',
    ]);
    Route::post('praise-list-all-time', [
        'uses' => 'PraiseController@postAll',
    ]);
});
