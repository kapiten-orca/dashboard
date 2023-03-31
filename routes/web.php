<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PtbController;


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

// Route::get('/ptb', function () {
//     return view('ptb.index');
// });

Route::get('/ptb', [PtbController::class, 'index']);



Route::get('/akademik', function () {
    return view('akademik.index');
});

Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/register', function () {
    return view('auth.register');
});
