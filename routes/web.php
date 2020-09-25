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
    return view('welcome');
});

Auth::routes();

Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'], function () {
    return abort(404); });

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group([
    'middleware'    => ['web','auth']
], function() {
    Route::get('/create-shipment/{token}', [App\Http\Controllers\HomeController::class, 'pageShipmentCreate'])->name('page_create_shipment');
    Route::post('/create-shipment/', [App\Http\Controllers\HomeController::class, 'pageShipmentCreatePost'])->name('page_create_shipment_post');
    Route::get('create-item/{id}', [App\Http\Controllers\HomeController::class, 'pageItemCreate'])->name('page_create_item');
    Route::post('create-item/', [App\Http\Controllers\HomeController::class, 'pageItemCreatePost'])->name('page_create_item_post');
    Route::get('delete-items/{id}', [App\Http\Controllers\HomeController::class, 'pageItemDelete'])->name('page_delete_item');
    Route::post('delete-items/', [App\Http\Controllers\HomeController::class, 'pageItemDeletePost'])->name('page_delete_item_post');
});
