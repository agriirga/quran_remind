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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/random_ayat', 'QuranController@randomAyat');
Route::get('/get_full_surat/{surat}', 'QuranController@fullSurat');
Route::get('/get_specific_ayat/{surat}/{ayat}','QuranController@specificAyat');
