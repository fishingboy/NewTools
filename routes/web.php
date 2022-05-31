<?php

use Illuminate\Support\Facades\Route;
//use ServiceResponse;

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

//Route::get('/', function () {
//    //ServiceResponse::parseStatus(200, 'Success', []);
//    return view('welcome');
//});


Route::any('/', [App\Http\Controllers\TextEditor::class, 'index'])->name('text_editor');
Route::any('test', [App\Http\Controllers\Test::class, 'index'])->name('test');
