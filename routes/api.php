<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/trackers', 'ApiController@trackers');

Route::get('/search', 'ApiController@search');
Route::get('/search/url', 'ApiController@getSearchUrl');
Route::post('/search/parse', 'ApiController@parseSearchResult');

Route::get('/media', 'ApiController@media');
Route::get('/media/urls', 'ApiController@getMediaUrls');
Route::post('/media/parse', 'ApiController@parseMedia');

Route::post('/download', 'ApiController@download');
Route::get('/download', 'ApiController@download');
Route::post('/download/file', 'ApiController@downloadFromFile');
