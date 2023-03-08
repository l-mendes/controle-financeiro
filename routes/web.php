<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubCategoryController;
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

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/transactions', function () {
        return view('dashboard');
    })->name('transactions');

    Route::get('/inbound', function () {
        return view('dashboard');
    })->name('inbound');

    Route::get('/outbound', function () {
        return view('dashboard');
    })->name('outbound');

    Route::resource('categories', CategoryController::class)->except(['edit']);

    Route::group(['prefix' => 'categories'], function () {
        Route::post('{category}/sub-categories/store', [SubCategoryController::class, 'store'])->name('categories.sub-categories.store');
        Route::put('{category}/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('categories.sub-categories.update');
        Route::delete('{category}/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('categories.sub-categories.destroy');
    });
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
