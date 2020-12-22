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
    Route::get('/disposisi/{disposisi}/{response}', 'DisposisiSuratMasukController@cetakDisposisi');
    Route::get('/disposisi', 'DisposisiSuratMasukController@index');
    Route::post('{surat}/disposisi', 'DisposisiSuratMasukController@store');
    Route::get('/disposisi/users', 'DisposisiSuratMasukController@disposisi');
    Route::get('/disposisi/{disposisi}', 'DisposisiSuratMasukController@show');
    Route::put('/disposisi/{disposisi}', 'DisposisiSuratMasukController@update');
    Route::delete('/disposisi/{disposisi}', 'DisposisiSuratMasukController@destroy');
});

/*
|--------------------------------------------------------------------------
| Disposisi Surat Keluar Routes
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
Route::get('/surat-masuk/{surat_masuk}/detail-surat/{response}', 'SuratMasukController@detailSurat')->middleware('jwt.auth');
Route::get('/surat-masuk/{surat_masuk}/tanda-terima/{response}', 'SuratMasukController@tandaTerima')->middleware('jwt.auth');
Route::resource('/surat-masuk', 'SuratMasukController')->middleware('jwt.auth')->except('store', 'create', 'edit');
Route::post('/surat-masuk', 'SuratMasukController@store')->middleware(['jwt.auth');

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
| Staff Min File Routes
|--------------------------------------------------------------------------
|
*/
Route::post('/staffmin/upload-file/{suratId}', 'StaffMinFileController@store')->middleware('jwt.auth');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
*/

Route::post('/register', 'UserController@store');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
