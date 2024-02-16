<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\API\FileUploadController;

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
// Route::get('api/files/download/{id}', [FileUploadController::class, 'downloadFile']);
// Route::get('api/files/view/{id}', [FileUploadController::class, 'viewFile']);
// Route::post('/api/upload', 'API\FileUploadController@upload');
Route::get('/search', 'Api\SearchController@search'); //search
Route::get('/items/expiring', 'API\ItemController@getExpiringItems');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('/category', 'API\CategoryController');
Route::apiResource('/items', 'API\ItemController');
Route::get('/related/{id}', 'API\CategoryController@related');
