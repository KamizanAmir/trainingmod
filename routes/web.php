<?php


Route::get('/', function () {
    return redirect()->to('/intro');
});

Auth::routes(['register' => true]); // Kamizan check sini untuk disable register

Route::get('{parh}', 'HomeController@index')->where('path', '([A-z\d-\/_.]+)?');
Route::fallback(function () {
    return view('intro');
});
Route::get('/{any}', function () {
    return view('/intro');
})->where('any', '.*');

