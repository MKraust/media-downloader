<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/trackers', 'ApiController@trackers');
Route::get('/search', 'ApiController@search');
Route::get('/media', 'ApiController@media');
Route::post('/download', 'ApiController@download');
Route::get('/download', 'ApiController@download');
