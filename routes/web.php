<?php

use App\Http\Controllers\AutorController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\LibroController;
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
    return view('modulos.home');
});
Route::get('/admin-libros', function () {
    return view('modulos.home');
});

Route::get('/admin-editoriales', function () {
    return view('modulos.home');
});
Route::get('/curriculum_yecid_tovar', function () {
    return view('modulos.home');
});

Route::resource('crudLibros', LibroController::class);
Route::resource('crudAutores', AutorController::class);
Route::resource('crudEditoriales', EditorialController::class);
