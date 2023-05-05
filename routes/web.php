<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmagController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false, 'password.request' => false, 'reset' => false]);


Route::group(['middleware' => 'auth'], function () {
    Route::redirect('/', '/acasa');

    Route::view('/acasa', 'acasa');

    Route::group(['middleware' => 'auth'], function () {
    });

    Route::view('/file-manager', 'vendor/file-manager/fmButton');

    Route::group(['prefix' => 'laravel-filemanager'], function (){
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });


    Route::get('/emag/categorii/citire-din-emag', [EmagController::class, 'citireCategoriiDinEmag']);
    Route::get('/emag/categorii/vizualizare-in-aplicatie', [EmagController::class, 'vizualizareCategoriiInAplicatie']);

    Route::get('/emag/produse', [EmagController::class, 'indexProduse']);

    Route::get('/emag/produse/adauga', [EmagController::class, 'adauga']);
    Route::get('/emag/produse/{produsId}/editare', [EmagController::class, 'editare']);
    Route::get('/emag/produse/{produsId}/actualizare-stoc/{stoc}', [EmagController::class, 'actualizareStocProdus']);
});
// v


