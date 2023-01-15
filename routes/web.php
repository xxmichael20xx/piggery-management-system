<?php

use App\Http\Controllers\AdminController;
use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group([ 'middleware' => 'auth.admin' ], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/pigs', [AdminController::class, 'pigs'])->name('admin.pigs');
    Route::post('/new_pig', [AdminController::class, 'newPig'])->name('admin.new_pig');
    Route::post('/update_pig/{id}', [AdminController::class, 'updatePig'])->name('admin.update_pig');
    Route::get('/pigs/{id}', [AdminController::class, 'showPig'])->name('admin.show_pig');
    Route::post('/add_to_orders/{id}', [AdminController::class, 'addToOrders'])->name('admin.add_to_orders');

    Route::get('/breeds', [AdminController::class, 'breeds'])->name('admin.breeds');
    Route::post('/new_breed', [AdminController::class, 'newBreed'])->name('admin.new_breed');
    Route::post('/update_breed', [AdminController::class, 'updateBreed'])->name('admin.update_breed');

    Route::get('/quarantine', [AdminController::class, 'quarantine'])->name('admin.quarantine');
    Route::post('/restore_pig', [AdminController::class, 'restorePig'])->name('admin.restore_pig');

    Route::get('/orders', function() {
        return redirect('/orders/pending_orders');
    });
    Route::get('/orders/{type?}', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/orders/update', [AdminController::class, 'orderUpdate'])->name('admin.order_update');

    Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');
});
