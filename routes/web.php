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
//
// Route::get('/', function () {
//     return view('welcome');
// });
//
// Route::get('/', 'AppController@index')->middleware(['auth.shop'])->name('home');
Route::get('/', 'UserController@test')->name('Test');
Route::get('/VerifyAffliatePromocode', 'UserController@AllCodes');
Route::get('/Test2', 'UserController@Test2')->name('Test2');
Route::get('/AllCodes', 'UserController@AllCodes')->name('AllCodes');
