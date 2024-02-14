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

// If you're using Laravel 8 and above, which supports namespaced routes out of the box

// If you're placing the FilesController directly under App\Http\Controllers, adjust the use statement accordingly.

Route::get('/items/expiring', 'API\ItemController@getExpiringItems');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('/category', 'API\CategoryController');
Route::apiResource('/items', 'API\ItemController');
Route::get('/related/{id}', 'API\CategoryController@related');
