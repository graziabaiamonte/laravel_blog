<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Frontend\ArticleController as FrontendArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
// use App\Http\Middleware\UserOwnsArticle;

// HOME PUBBLICA
Route::get('/', [FrontendArticleController::class, 'index'])->name('home');

// Parte di GESTIONE degli articoli (solo utenti loggati).
Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {
    
    Route::get('/', [ArticleController::class, 'index'])
    ->middleware(['verified'])
    ->name('dashboard');

    // Creare/pubblicare articoli: serve il permesso 'publish articles'
    // (ce l'hanno sia admin sia author).
    Route::resource('articles', ArticleController::class)
        ->only(['create', 'store'])
        ->middleware('permission:publish articles');
   
    // ---------------------------------------------------------------------------------------------
    // 
    // 1. versione senza alias, con il nome completo della classe del middleware e importazione con use in cima al file
    // 
    // Route::resource('articles', ArticleController::class)
    // ->only(['edit', 'update', 'destroy'])
    // ->middleware([UserOwnsArticle::class]);

    // 
    // 2. versione usando l'alias che ho registrato in bootstrap/app.php
    // 
    Route::resource('articles', ArticleController::class)
    ->only(['edit', 'update', 'destroy'])
    ->middleware(['owns.article']); 

    //  ---------------------------------------------------------------------------------------------
    
    // Gestione di categorie, tag e utenti: riservata al solo ruolo admin.
    Route::resource('categories', CategoryController::class)
        ->middleware('role:admin');

    Route::resource('tags', TagController::class)
        ->middleware('role:admin');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class)
        ->middleware('role:admin');

});

// Parte PUBBLICA degli articoli
Route::resource('articles', FrontendArticleController::class)->only(['index', 'show']);


require __DIR__.'/auth.php';
