<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', 'VueController@index')->where('any', '.*');