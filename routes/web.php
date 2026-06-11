<?php

use App\Enums\Permission;
use App\Enums\Role;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Frontend\ArticleController as FrontendArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// use App\Http\Middleware\UserOwnsArticle;

// HOME PUBBLICA
Route::get('/', [FrontendArticleController::class, 'index'])->name('home');

// CAMBIO LINGUA: salva la lingua scelta in sessione e torna alla pagina precedente.
// Il middleware SetLocale legge poi questa sessione a ogni richiesta, dando la
// priorità alla scelta manuale rispetto alla lingua del browser.
Route::get('/locale/{locale}', function (string $locale) {
    // Accetto solo le lingue effettivamente supportate, per sicurezza.
    if (in_array($locale, ['it', 'en'], true)) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('locale.switch');

// Parte di GESTIONE degli articoli (solo utenti loggati).
Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {

    Route::get('/', [ArticleController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    Route::resource('articles', ArticleController::class)
        ->only(['create', 'store']);

    // Cambio STATO (bozza <-> pubblicato): SOLO chi ha 'publish articles'
    Route::patch('articles/{article}/status', [ArticleController::class, 'updateStatus'])
        ->middleware('permission:'.Permission::PublishArticles->value)
        ->name('articles.status');

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

    Route::resource('categories', CategoryController::class)
        ->middleware('role:'.Role::Admin->value);

    Route::resource('tags', TagController::class)
        ->middleware('role:'.Role::Admin->value);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class)
        ->middleware('role:'.Role::Admin->value);

    Route::patch('comments/{comment}/approve', [CommentController::class, 'approve'])
        ->middleware('owns.comment')
        ->name('comments.approve');

    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])
        ->middleware('owns.comment')
        ->name('comments.destroy');

});

// Parte PUBBLICA degli articoli
Route::resource('articles', FrontendArticleController::class)->only(['index', 'show']);

Route::post('articles/{article}/comments', [CommentController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('comments.store');

require __DIR__.'/auth.php';
