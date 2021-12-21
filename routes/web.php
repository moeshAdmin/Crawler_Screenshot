<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
/*
Route::get('/', function () {
    return view('index');
});*/

Route::get('/test', function () {
    return 'Hello World';
});

Route::any('/', 'App\Http\Controllers\CrawlerController@index');
Route::post('/startUrlCrawler', 'App\Http\Controllers\CrawlerController@startUrlCrawler');
Route::post('/startBackground', 'App\Http\Controllers\CrawlerController@startBackground');
Route::post('/getPastUrlData', 'App\Http\Controllers\CrawlerController@getPastUrlData');
Route::post('/saveScreenshot', 'App\Http\Controllers\CrawlerController@saveScreenshot');
Route::post('/getUrlDetail', 'App\Http\Controllers\CrawlerController@getUrlDetail');


