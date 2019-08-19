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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home.index');
Route::get('/home', 'HomeController@index')->name('home');

/*
 * Contacts
 */
Route::get('/contacts', 'ContactController@index')->name('contacts.index');
Route::post('/contacts/add', 'ContactController@store')->name('contacts.store');
Route::put('/contacts/edit/{id}', 'ContactController@update')->name('contacts.update');
Route::delete('/contacts/delete/{id}', 'ContactController@destroy')->name('contacts.destroy');

// Send notification to Contact to opt in
Route::post('/contacts/notify/{id}', 'ContactController@notify')->name('contacts.notify');

// Opt in route for Contact
Route::post('/contacts/update/{id}/{token}', 'ContactController@optIn')->name('contacts.opt-in');

