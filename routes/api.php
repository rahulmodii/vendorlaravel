<?php

use Illuminate\Http\Request;


header('Access-Control-Allow-Origin: *');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1','namespace'=>'Api\V1'], function () {
        Route::post('/createvendor','VendorController@create');
        Route::get('/vendors','VendorController@vendors');
        Route::get('/search/{query}','VendorController@search');
        Route::get('/vendor/{id}','VendorController@show');
        Route::post('/vendoredit/{id}','VendorController@edit');
        Route::get('/countries','VendorController@countries');
});
