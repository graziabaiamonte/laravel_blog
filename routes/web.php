<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Frontend\ArticleController as FrontendArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UserOwnsArticle;

// HOME PUBBLICA: mostra TUTTI gli articoli (anche ai non loggati).
// Punta al controller della parte pubblica (cartella Frontend).
Route::get('/', [FrontendArticleController::class, 'index'])->name('home');

// Parte PUBBLICA degli articoli: index (lista) e show (dettaglio), senza login.
Route::resource('articles', FrontendArticleController::class)->only(['index', 'show']);

// Parte di GESTIONE degli articoli (solo utenti loggati).
// create/store: basta essere loggati.
Route::resource('articles', ArticleController::class)
    ->only(['create', 'store'])
    ->middleware('auth');

// edit/update/destroy: loggati E proprietari dell'articolo (middleware owns.article).
Route::resource('articles', ArticleController::class)
    ->only(['edit', 'update', 'destroy'])
    ->middleware(['auth', UserOwnsArticle::class]);

Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);

// DASHBOARD: mostra SOLO gli articoli dell'utente loggato.
// Non è più una semplice closure: ora usa ArticleController@index, che filtra
// gli articoli con lo scope ownedBy() e passa $articles alla view 'dashboard'.
Route::get('/dashboard', [ArticleController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    // 'auth'     → controlla che l'utente abbia fatto il login.
    // 'verified' → controlla che l'utente abbia verificato l'email.

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ⚠️ DIDATTICO: gestione di TUTTI gli utenti da parte di qualsiasi loggato.
    // In futuro andrà protetta con un middleware di ruolo (es. 'role:admin').
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
