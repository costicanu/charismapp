<?php

use Illuminate\Support\Facades\Route;

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

Route::resource('orders','OrderController');
Route::get('newOrder/{project_id}/{order_id}','OrderController@newOrder');
Route::get('test/','OrderController@test');
Route::get('rewriteDatabasePrices/{project_id}','OrderController@rewriteDatabasePrices');
Route::get('rewriteDatabaseNomenclator/{project_id}','OrderController@rewriteDatabaseNomenclator');
Route::get('companyExistsInCharisma/{cod_firma}','OrderController@companyExistsInCharisma');
Route::get('getCityIdFromCharisma/','OrderController@getCityIdFromCharisma');

Route::get('addClientToCharisma/','OrderController@addClientToCharisma');
Route::get('matchCity/{city_name}','OrderController@matchCity');
Route::get('addClientToCharisma','OrderController@addClientToCharisma');
Route::get('createCharismaOrder/','OrderController@createCharismaOrder');
Route::post('adaugaComandaPersoanaFizica/','OrderController@adaugaComandaPersoanaFizica');
Route::post('adaugaComandaPersoanaJuridica/','OrderController@adaugaComandaPersoanaJuridica');

