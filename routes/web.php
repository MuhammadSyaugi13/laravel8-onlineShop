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
// Route::get('/debug-sentry', function () {
//     throw new Exception('My first Sentry error!');
// });
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
Route::get('/detail/{id}', [App\Http\Controllers\DetailController::class, 'index'])->name('detail');
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart');
Route::get('/success', [App\Http\Controllers\CartController::class, 'success'])->name('success');
Route::get('/register/success', [App\Http\Controllers\Auth\RegisterController::class, 'success'])->name('register-success');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard-product', [App\Http\Controllers\DashboardProductController::class, 'index'])
        ->name('dashboard-product');

// Route::get('/', function () {
//     return view('pages.home');
// });


Auth::routes();
