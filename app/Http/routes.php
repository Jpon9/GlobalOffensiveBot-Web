<?php

/**
 * AUTHENTICATION
 */

// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('register', 'Auth\AuthController@getRegister');
Route::post('register', 'Auth\AuthController@postRegister');

// No permissions at all
Route::get('no-permissions', function() { return view('errors.no-permissions'); });

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
	// User listings
	Route::get('users', 'AdminUsersController@index');
	Route::get('users/{id}', 'AdminUsersController@loadUser');
	Route::post('users/{id}', 'AdminUsersController@updateUser');

	// User creation
	Route::get('register', function() { return view('admin.register'); });
	Route::post('register', 'Auth\AuthController@adminRegisterUser');
});

Route::group(['middleware' => 'auth'], function() {
	// Home page
	Route::get('/', function () { return view('home'); });

	// Status and Metadata
	Route::get('bot/status', 'BotController@status');
	Route::get('bot/metadata', 'BotController@metadata');

	// Basic Config
	Route::get('basic-config', 'BasicConfigController@index');
	Route::post('basic-config', 'BasicConfigController@updateConfig');

	// Sidebar
	Route::get('sidebar', 'SidebarController@index');
	Route::post('sidebar', 'SidebarController@updateSidebar');

	// Stylesheet
	Route::get('stylesheet', 'StylesheetController@index');
	Route::post('stylesheet', 'StylesheetController@updateStylesheet');

	// Demonyms
	Route::get('demonyms', 'DemonymsController@index');
	Route::post('demonyms', 'DemonymsController@updateDemonyms');

	// Notices
	Route::get('notices', 'NoticesController@index');
	Route::get('notices/fetch', 'NoticesController@getNotices');
	Route::post('notices', 'NoticesController@updateNotices');

	// Log
	Route::get('log/webpanel', function() { return view('log.webpanel'); });
	// Mod actions log
    Route::get('log/mod', 'ModlogController@index');
    Route::post('log/mod/listing', 'ModlogController@filter');
    Route::match(['get', 'post'], 'log/mod/search', 'ModlogController@query');
	// Modmail
    Route::get('log/modmail', 'ModmailController@index');
    Route::match(['get', 'post'], 'log/modmail/listing', 'ModmailController@listing');
});

