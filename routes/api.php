<?php

use Illuminate\Support\Facades\Route;

Route::get('/trackers', 'InfoController@trackers');
Route::get('/info/storage', 'InfoController@storage');

Route::get('/search', 'TrackerController@search');
Route::get('/media', 'TrackerController@media');
Route::post('/download', 'TrackerController@download');
Route::get('/download', 'TrackerController@download');

Route::post('/favorites/add', 'FavoritesController@addToFavorites');
Route::get('/favorites/add', 'FavoritesController@addToFavorites');
Route::get('/favorites/list', 'FavoritesController@getFavorites');
Route::post('/favorites/remove', 'FavoritesController@removeFromFavorites');

Route::get('/download/list', 'DownloadsController@getDownloads');
Route::post('/download/delete', 'DownloadsController@deleteDownload');
Route::post('/download/pause', 'DownloadsController@pauseDownload');
Route::post('/download/resume', 'DownloadsController@resumeDownload');

Route::get('/download/finish', 'DownloadsController@finishDownload');