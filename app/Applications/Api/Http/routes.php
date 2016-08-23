<?php

$regexOneUUID = '[a-zA-Z0-9\-]{36}';
$regexOneSLUG = '[a-z0-9_\-]+';
$regexOneLANG = '[a-z]{2}(\-[a-zA-Z]{2})?';
$regexOneKEYI18N = '(\w+\.?\w+)+';
$regexOneCURR = '[A-Z]{3}';
$regexOneEXCH = '[A-Z]{3}\:[A-Z]{3}';

$regexManyUUID = '('.$regexOneUUID.')(,'.$regexOneUUID.')*';
$regexManySLUG = '('.$regexOneSLUG.')(,'.$regexOneSLUG.')*';
$regexManyCURR = '('.$regexOneCURR.')(,'.$regexOneCURR.')*';
$regexManyEXCH = '('.$regexOneEXCH.')(,'.$regexOneEXCH.')*';

Route::get('currencies', ['as' => 'currencies', 'uses' => 'CurrenciesController@index']);
//Route::post('currencies', ['as' => 'currencies.store', 'uses' => 'CurrenciesController@store']);
Route::get('currencies/default', ['as' => 'currencies.default', 'uses' => 'CurrenciesController@getDefault']);

//id format -> BRL or USD
Route::get('currencies/{ids}', ['as' => 'currencies.show', 'uses' => 'CurrenciesController@show'])->where(['ids' => $regexManyCURR]);

//id format -> [BRL,USD]
Route::get('exchanges', ['as' => 'exchanges', 'uses' => 'ExchangesController@index']);
Route::get('exchanges/{ids}', ['as' => 'exchanges.show', 'uses' => 'ExchangesController@show'])->where(['ids' => $regexManyEXCH]);

//id format -> UUID
Route::get('exchanges_historics', ['as' => 'exchanges_historics', 'uses' => 'ExchangesHistoricsController@index']);
Route::get('exchanges_historics/{ids}', ['as' => 'exchanges_historics.show', 'uses' => 'ExchangesHistoricsController@show'])->where(['ids' => $regexManyUUID]);

//id format -> UUID
Route::get('users', ['as' => 'users', 'uses' => 'UsersController@index']);
Route::post('users', ['as' => 'users.store', 'uses' => 'UsersController@store']);
Route::get('users/{ids}', ['as' => 'users.show', 'uses' => 'UsersController@show'])->where(['ids' => $regexManyUUID]);
Route::get('users/{ids}/attach_roles/{idsr}', ['as' => 'users.attach_roles', 'uses' => 'UsersController@attachRoles'])->where(['ids' => $regexManyUUID, 'idsr' => $regexManySLUG]);
Route::get('users/{ids}/detach_roles/{idsr}', ['as' => 'users.detach_roles', 'uses' => 'UsersController@detachRoles'])->where(['ids' => $regexManyUUID, 'idsr' => $regexManySLUG]);

//id format -> SLUG
Route::get('roles', ['as' => 'roles', 'uses' => 'RolesController@index']);
Route::get('roles/{id}', ['as' => 'roles.show', 'uses' => 'RolesController@show'])->where(['id' => $regexManySLUG]);

//id format -> SLUG
Route::get('permissions', ['as' => 'permissions', 'uses' => 'PermissionsController@index']);
Route::get('permissions/{id}', ['as' => 'permissions.show', 'uses' => 'PermissionsController@show'])->where(['id' => $regexManySLUG]);

//id format -> SLUG
Route::post('translations', ['as' => 'translations.store', 'uses' => 'TranslationsController@store']);
Route::get('translations/refresh/{id}', ['as' => 'translations.refresh', 'uses' => 'TranslationsController@refresh'])->where(['id' => $regexOneLANG]);
Route::get('translations/{id}', ['as' => 'translations.show', 'uses' => 'TranslationsController@show'])->where(['id' => $regexOneLANG]);
Route::get('translations/{id}.json', ['as' => 'translations.showfile', 'uses' => 'TranslationsController@showfile'])->where(['id' => $regexOneLANG]);
Route::delete('translations/{id}/{key}', ['as' => 'translations.delete', 'uses' => 'TranslationsController@delete'])->where(['id' => $regexOneLANG, 'key' => $regexOneKEYI18N]);

Route::get('test', ['as' => 'test', 'uses' => 'RolesController@test']);