<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
Route::get('/{contact}', [ContactController::class, 'show'])->name('contacts.show');
Route::post('/', [ContactController::class, 'store'])->name('contacts.store');
Route::put('/{contact}', [ContactController::class, 'update'])->name('contacts.update');
Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

Route::post('/{contact}/interactions', [InteractionController::class, 'store'])->name('interactions.store');
Route::delete('/{contact}/interactions/{interaction}', [InteractionController::class, 'destroy'])->name('interactions.destroy');

Route::post('/{contact}/sales', [SaleController::class, 'store'])->name('sales.store');
Route::delete('/{contact}/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
