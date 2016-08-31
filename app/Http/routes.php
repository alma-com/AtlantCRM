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
        Route::post('roles/destroyItems', ['as' => 'roles.destroyItems', 'uses' => 'RoleController@destroyItems']);
        Route::post('roles/updateItems', ['as' => 'roles.updateItems', 'uses' => 'RoleController@updateItems']);

        Route::resource('task-reports', 'TaskReportsController');
        Route::post('task-reports/destroyItems', ['as' => 'task-reports.destroyItems', 'uses' => 'TaskReportsController@destroyItems']);
        Route::post('task-reports/updateItems', ['as' => 'task-reports.updateItems', 'uses' => 'TaskReportsController@updateItems']);

        Route::get('/example', function () {
            return view('example');
        });
    });
});
