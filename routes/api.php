<?php

use Illuminate\Http\Request;


Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@token');

Route::middleware('auth:api')->group(function () {
    Route::delete('notes/delete/{id}', 'API\NotesController@delete');
    Route::post('notes/store', 'API\NotesController@store');
    Route::get('notes/{id}', 'API\NotesController@edit');
    Route::post('notes/update/{id}', 'API\NotesController@update');
    Route::post('/logout', 'API\AuthController@logout');
});
Route::get('allnotes','API\NotesController@index');
