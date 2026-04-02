<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\NotebookController;
use App\Http\Controllers\NotebookSearchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NotebookController::class, 'index'])->name('products.index');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

Route::prefix('notebook')->group(function () {
  Route::get('/{notebook}', [NotebookController::class, 'show'])->name('notebook.show');
  Route::post('/', [NotebookController::class, 'addFeedbacks'])->name('notebook.addFeedbacks');
});

Route::prefix('/basket')->group(function () {
  Route::get('/', [BasketController::class, 'index'])->name('basket.index');
  Route::post('/', [BasketController::class, 'store'])->name('basket.store');
  Route::patch('/{id}/increase', [BasketController::class, 'increase'])->name('basket.increase');
  Route::patch('/{id}/decrease', [BasketController::class, 'decrease'])->name('basket.decrease');
  Route::delete('/{id}', [BasketController::class, 'destroy'])->name('basket.destroy');
  Route::get('/get-cart-data', [BasketController::class, 'getCartData'])->name('basket.getCartData');
  Route::get('/order', [BasketController::class, 'order'])->name('basket.order');

  // Получение состояния корзины
  Route::get('/state', [BasketController::class, 'getState'])
    ->name('basket.get_state');
});

Route::prefix('/search')->group(function () {
  Route::get('/', [NotebookSearchController::class, 'index'])->name('search.results');
  Route::get('/notebooks', [NotebookSearchController::class, 'search'])->name('search.notebooks');
  Route::get('/{line}', [NotebookSearchController::class, 'index'])->name('search.index');
});


Route::get('/dashboard', function () {
  return redirect('products.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
