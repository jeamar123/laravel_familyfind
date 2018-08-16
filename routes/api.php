<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', 'AuthController@login');
Route::post('/signup', 'AuthController@register');
Route::get('/check_session', 'AuthController@checkSessionStatus');
Route::get('/get_session', 'AuthController@get_session');
Route::post('/logout', 'AuthController@logout');

Route::get('/users', 'UserController@getUsers');
Route::get('/users/{id}', 'UserController@getUsersbyID');
Route::get('/users/contacts/{id}', 'UserController@getUserContacts');
Route::post('/users/search', 'UserController@getUsersbyName');
Route::post('/users/update', 'UserController@updateUser');
Route::post('/users/update_password', 'UserController@updateUserPassword');
Route::post('/users/add_remove_contact', 'UserController@addRemoveUserContact');




// Route::get('/get_pages', 'PagesController@getAllPages');
// Route::get('/get_pages/{id}', 'PagesController@getPageByID');
// Route::post('/add_page', 'PagesController@addPage');
// Route::post('/update_page', 'PagesController@updatePage');
// Route::get('/delete_page/{id}', 'PagesController@deletePage');