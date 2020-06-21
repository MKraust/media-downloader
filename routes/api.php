<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/trackers', 'InfoController@trackers');

Route::get('/search', 'TrackerController@search');
Route::get('/media', 'TrackerController@media');
Route::post('/download', 'TrackerController@download');
Route::get('/download', 'TrackerController@download');

Route::get('/search/url', 'BlockedTrackerController@getSearchUrl');
Route::post('/search/parse', 'BlockedTrackerController@parseSearchResult');
Route::get('/media/urls', 'BlockedTrackerController@getMediaUrls');
Route::post('/media/parse', 'BlockedTrackerController@parseMedia');
Route::post('/download/file', 'BlockedTrackerController@downloadFromFile');

Route::get('/download/notify/finish', 'NotificationController@notifyAboutFinishedDownload');

Route::post('/favorites/add', 'FavoritesController@addToFavorites');
Route::get('/favorites/add', 'FavoritesController@addToFavorites');
Route::get('/favorites/list', 'FavoritesController@getFavorites');
Route::post('/favorites/remove', 'FavoritesController@removeFromFavorites');

Route::get('/download/list', 'DownloadsController@getDownloads');