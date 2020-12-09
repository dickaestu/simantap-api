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
| Auth Routes
|--------------------------------------------------------------------------
|
*/

Route::post('/register', 'UserController@store');
Route::post('/surat-keluar/create', 'SuratKeluarController@store');
Route::put('/surat-keluar/update/{id}', 'SuratKeluarController@update');
Route::delete('/surat-keluar/delete/{id}', 'SuratKeluarController@destroy');
Route::post('/login', 'AuthController@login');
