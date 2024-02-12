<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/items/expiring', 'API\ItemController@getExpiringItems');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('/category', 'API\CategoryController');
Route::apiResource('/items', 'API\ItemController');
Route::get('/related/{id}', 'API\CategoryController@related');
