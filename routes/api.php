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
| Admin Manajemen User Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.auth', 'is.admin']], function () {
    Route::get('/user', 'UserController@index');
    Route::get('/user/{user}', 'UserController@show');
    Route::put('/user/{user}', 'UserController@update');
});

/*
|--------------------------------------------------------------------------
| Paur Manajemen User Routes
|--------------------------------------------------------------------------
|
*/

Route::resource('/paur/user', 'PaurController')->middleware(['jwt.auth', 'beyond.paur'])->except('create', 'edit');

/*
|--------------------------------------------------------------------------
| Disposisi Surat Masuk Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => '/surat-masuk', 'middleware' => 'jwt.auth'], function () {
    Route::get('/disposisi/{disposisi}/{response}', 'DisposisiSuratMasukController@cetakDisposisi');
    Route::get('/history/{surat}', 'DisposisiSuratMasukController@history');
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
Route::get('/surat-masuk/done', 'SuratMasukController@suratMasukSuccess')->middleware('jwt.auth');
Route::resource('/surat-masuk', 'SuratMasukController')->middleware('jwt.auth')->except('store', 'create', 'edit');
Route::post('/surat-masuk', 'SuratMasukController@store')->middleware('jwt.auth');

/*
|--------------------------------------------------------------------------
| Surat Keluar Routes
|--------------------------------------------------------------------------
|
*/


Route::resource('/surat-keluar', 'SuratKeluarController')->middleware('jwt.auth')->except('create', 'edit');
Route::post('/surat-keluar/confirm/{id}', 'SuratKeluarController@confirm')->middleware('jwt.auth');
Route::get('/surat-keluar/history/{id}', 'SuratKeluarController@history')->middleware('jwt.auth');

/*
|--------------------------------------------------------------------------
| Bagian Routes
|--------------------------------------------------------------------------
|
*/


Route::resource('/bagian', 'BagianController')->middleware('jwt.auth')->except('create', 'edit');

/*
|--------------------------------------------------------------------------
| SubBagian Routes
|--------------------------------------------------------------------------
|
*/


Route::resource('/sub-bagian', 'SubBagianController')->middleware('jwt.auth')->except('create', 'edit');

/*
|--------------------------------------------------------------------------
| Roles Routes
|--------------------------------------------------------------------------
|
*/


Route::resource('/roles', 'RolesController')->middleware('jwt.auth')->except('create', 'edit');

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

Route::post('/register', 'UserController@store')->middleware('jwt.auth');
Route::get('/profile', 'UserController@profile')->middleware('jwt.auth');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
