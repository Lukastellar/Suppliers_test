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

Route::group(['prefix' => 'api'], function(){

    // * - Prikazivanje svih Dobavljača
    Route::get('/index ', [SupplierController::class, 'index']);

    // * - Izmena naziva Dobavljača
    Route::get('/update/{id}/{name}', [SupplierController::class, 'update']);

    // * - Brisanje Dobavljača
    Route::get('/destroy/{id}', [SupplierController::class, 'destroy']);
});

   // * - prikazivanje svih proizvoda
   // * - prikazivanje svih proizvoda koje poseduje određeni Dobavljač
   // * - izmena proizvoda
   // * - brisanje proizvoda
