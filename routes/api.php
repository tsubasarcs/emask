<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/')->name('api.v1.')->group(function () {
    Route::post('shops', 'App\Http\Controllers\Api\V1\ShopController@store')->name('shops.post');
    Route::post('messages', 'App\Http\Controllers\Api\V1\MessageController@store')
        ->name('messages.post');
    Route::post('messages/search', 'App\Http\Controllers\Api\V1\MessageController@search')
        ->name('messages.search');
});
