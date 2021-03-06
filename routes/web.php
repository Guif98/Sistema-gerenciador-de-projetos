<?php

use GuzzleHttp\Middleware;
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


Route::get('/', 'SubProjetoControlador@home');
Route::post('/', 'SubProjetoControlador@votar')->name('votar');

Route::get('/projetos', 'ProjetoControlador@index')->middleware('auth')
->name('projetos');

Route::post('/novoProjeto', 'ProjetoControlador@store');
Route::get('/novoProjeto', 'ProjetoControlador@create');
Route::get('projetos/{id}/edit', 'ProjetoControlador@edit');
Route::patch('projetos/{id}','ProjetoControlador@update');
Route::get('projetos/delete/{id}', 'ProjetoControlador@destroy');

Route::get('/subprojetos/{projeto_id}', 'SubProjetoControlador@index')->name('subprojetos');
Route::get('/subprojetos/{projeto_id}/formProjeto', 'SubProjetoControlador@create');
Route::get('/subprojetos/{projeto_id}/ver/{id}', 'SubProjetoControlador@show');
Route::post('/subprojetos/formProjeto', 'SubProjetoControlador@store');
Route::get('/subprojetos/{projeto_id}/edit/{id}', 'SubProjetoControlador@edit');
Route::put('/subprojetos/{projeto_id}/{id}', 'SubProjetoControlador@update');
Route::get('/subprojetos/{id}/delete', 'SubProjetoControlador@destroy');
Route::get('/subprojetos/{projeto_id}/addFoto/{id}', 'SubProjetoControlador@fotos')->name('addFoto');
Route::get('/subprojetos/{projeto_id}/delete/{id}', 'SubProjetoControlador@deletarFoto')->name('deletarFoto');
Route::post('/subprojetos/{projeto_id}/{id}', 'SubProjetoControlador@storeFoto');

Route::get('/categorias/{projeto_id}', 'CategoriasControlador@index')->name('categorias');
Route::get('/categorias/{projeto_id}/criar', 'CategoriasControlador@create');
Route::post('/categorias/criar', 'CategoriasControlador@store');
Route::get('/categorias/{projeto_id}/edit/{id}', 'CategoriasControlador@edit');
Route::patch('/categorias/{projeto_id}/{id}', 'CategoriasControlador@update');
Route::get('/categorias/delete/{projeto_id}', 'CategoriasControlador@destroy');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
