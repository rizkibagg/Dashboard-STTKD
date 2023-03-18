<?php

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
    return view('Dashboard.login',[
        "title" => "Login"
    ]);
});

Route::get('/register', function () {
    return view('Dashboard.register',[
        "title" => "Register"
    ]);
});

Route::get('/home', function () {
    return view('Dashboard.home',[
        "title" => "Home"
    ]);
});

Route::get('/akademik', function () {
    return view('Dashboard.akademik',[
        "title" => "Akademik"
    ]);
});

Route::get('/sdm', function () {
    return view('Dashboard.sdm',[
        "title" => "SDM"
    ]);
});

Route::get('/ptb', function () {
    return view('Dashboard.ptb',[
        "title" => "PTB"
    ]);
});

Route::get('/about', function () {
    return view('Dashboard.about',[
        "title" => "About"
    ]);
});
