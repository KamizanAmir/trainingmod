<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FileUploadController;

Route::get('/', function () {
    return redirect()->to('/intro');
});
// Route::post('/api/upload', 'API\FileUploadController@upload');
// // Add new routes for retrieving files
// Route::get('api/files', [FileUploadController::class, 'listFiles']);
// Route::get('api/files/{id}', [FileUploadController::class, 'getFile']);
// Route::get('api/files/download/{id}', [FileUploadController::class, 'downloadFile']);
// Route::get('api/files/view/{id}', [FileUploadController::class, 'viewFile']);

Auth::routes(['register' => true]); // Kamizan check sini untuk disable register

Route::get('{parh}', 'HomeController@index')->where('path', '([A-z\d-\/_.]+)?');
Route::fallback(function () {
    return view('home');
});
