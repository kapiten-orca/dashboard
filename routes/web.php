<?php

use App\Http\Controllers\akademikController;
use App\Http\Controllers\ptbController;
use App\Http\Controllers\sessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index', function () {
    return view('home.index');
});

Route::get('/ptb', function () {
    return view('ptb.index');
});

Route::get('/sesi', [sessionController::class, 'index']);
Route::post('/sesi/login', [sessionController::class, 'login']);
Route::get('/sesi/logout', [sessionController::class, 'logout']);
Route::get('/sesi/register', [sessionController::class, 'register']);
Route::post('/sesi/create', [sessionController::class, 'create']);

Route::get('/akademik', [akademikController::class, 'index']);
Route::get('/akademik/prodi', [akademikController::class, 'prodi']);
Route::get('/ptb', [ptbController::class, 'index']);
Route::get('/ptb/hreg', [ptbController::class, 'hreg']);
Route::get('/ptb/taruna', [ptbController::class, 'dataTaruna']);

Route::post('/ptb/filter', [ptbController::class, 'dataFilter']);

