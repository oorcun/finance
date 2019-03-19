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
    return redirect()->action("HomeController@index");
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post("themes", "ThemeController@update")->name("themes.update");

Route::get("stocks", "StockController@index")->name("stocks.index");
Route::post("stocks/get_prices", "StockController@getPrices")->name("stocks.get_prices");
Route::post("stocks/start_fetcher", "StockController@startFetcher")->name("stocks.start_fetcher");
Route::post("stocks/stop_fetcher", "StockController@stopFetcher")->name("stocks.stop_fetcher");
Route::post("stocks/buy", "StockController@buy")->name("stocks.buy");
Route::post("stocks/sell", "StockController@sell")->name("stocks.sell");
