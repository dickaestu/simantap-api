<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
|--------------------------------------------------------------------------
| Disposisi Surat Masuk Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => '/surat-masuk', 'middleware' => 'jwt.auth'], function () {
    Route::get('/disposisi', 'DisposisiSuratMasukController@index');
    Route::post('{surat}/disposisi', 'DisposisiSuratMasukController@store');
    Route::get('/disposisi/{disposisi}', 'DisposisiSuratMasukController@show');
    Route::put('/disposisi/{disposisi}', 'DisposisiSuratMasukController@update');
    Route::delete('/disposisi/{disposisi}', 'DisposisiSuratMasukController@destroy');
});

/*
|--------------------------------------------------------------------------
| Disposisi Surat KEluar Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['prefix' => '/surat-keluar', 'middleware' => 'jwt.auth'], function () {
    Route::get('/disposisi', 'DisposisiSuratKeluarController@index');
    Route::post('{surat}/disposisi', 'DisposisiSuratKeluarController@store');
    Route::get('/disposisi/{disposisi}', 'DisposisiSuratKeluarController@show');
    Route::put('/disposisi/{disposisi}', 'DisposisiSuratKeluarController@update');
    Route::delete('/disposisi/{disposisi}', 'DisposisiSuratKeluarController@destroy');
});

/*
|--------------------------------------------------------------------------
| Surat Masuk Routes
|--------------------------------------------------------------------------
|
*/

Route::resource('/surat-masuk', 'SuratMasukController')->middleware('jwt.auth')->except('create', 'edit');


/*
|--------------------------------------------------------------------------
| Surat Keluar Routes
|--------------------------------------------------------------------------
|
*/


Route::resource('/surat-keluar', 'SuratKeluarController')->middleware('jwt.auth')->except('create', 'edit');

/*
|--------------------------------------------------------------------------
| Bagian Routes
|--------------------------------------------------------------------------
|
*/


Route::resource('/bagian', 'BagianController')->middleware('jwt.auth')->except('create', 'edit');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
*/

Route::post('/register', 'UserController@store');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
