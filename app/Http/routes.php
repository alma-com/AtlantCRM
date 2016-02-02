<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::auth();

	Route::group(['middleware' => ['auth']], function () {
		Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
		
		
		Route::resource('users', 'UserController');
		Route::post('users/destroyItems', ['as' => 'users.destroyItems', 'uses' => 'UserController@destroyItems']);
		Route::post('users/updateItems', ['as' => 'users.updateItems', 'uses' => 'UserController@updateItems']);
		
		Route::resource('roles', 'RoleController');
		

		Route::get('/example', function () {
			return view('example');
		});
	});
});
