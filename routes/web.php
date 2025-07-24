<?php

use Illuminate\Support\Facades\Route;

// Todas as rotas do frontend Vue devem retornar a view welcome
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('welcome');
    })->name('dashboard');
});
