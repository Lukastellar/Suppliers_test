<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplyController;

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

Route::group(['prefix' => 'supplier'], function(){

    // * - Prikazivanje svih Dobavljača
    Route::get('/index ', [SupplierController::class, 'index']);

    // * - Izmena naziva Dobavljača
    Route::get('/update/{id}/{name}', [SupplierController::class, 'update']);

    // * - Brisanje Dobavljača
    Route::get('/destroy/{id}', [SupplierController::class, 'destroy']);
});

Route::group(['prefix' => 'product'], function(){

    // * - prikazivanje svih proizvoda
    Route::get('/index', [SupplyController::class, 'index']);

    // * - prikazivanje svih proizvoda koje poseduje određeni Dobavljač
    // Parametar moze biti ID ili name supplier-a
    Route::get('/show/{search}', [SupplyController::class, 'show']);

    // * - izmena proizvoda
    // Može se upisati više column_name-ova u formi /update/{id}?{column_name=value}&...
    Route::get('/update/{id}', [SupplyController::class, 'update']);

    // * - brisanje proizvoda
    Route::get('/destroy/{id}', [SupplyController::class, 'destroy']);

    //CSV Generator
    Route::get('/generate/{search}', [SupplyController::class, 'generateCSV']);
});

