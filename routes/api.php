<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/trackers', 'ApiController@trackers');
Route::get('/search', 'ApiController@search');
Route::get('/media', 'ApiController@media');
