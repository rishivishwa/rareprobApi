<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetDataController;
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





Route::get('list/',[GetDataController::class,'show']);
// Route::get('newlist',[GetDataController::class,'imageData']);
// Route::get('nextdata',[GetDataController::class,'next']);

Route::get('new',[GetDataController::class,'newdata']);

Route::get('method/{}',[GetDataController::class,'list_for_autocomplete']);







