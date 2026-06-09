<?php

// verificare la notifica come OGGETTO ISOLATO, senza database,
// senza HTTP e senza eventi

use App\Models\Article;
use App\Notifications\ArticleCreated;

// la notifica deve essere consegnata SOLO via email (canale 'mail').
test('la notifica viene consegnata sul canale mail', function () {
    $article = new Article;

    $notification = new ArticleCreated($article);

    // Ci aspettiamo l'array con il solo canale 'mail'.
    expect($notification->via(new Article))->toBe(['mail']);
});

// il metodo toArray() deve restituire id e titolo dell'articolo.
test('toArray restituisce id e titolo dell\'articolo', function () {
    $article = new Article;
    $article->id = 99;
    $article->title = 'mio primo articolo di test';

    $notification = new ArticleCreated($article);

    expect($notification->toArray(new Article))->toBe([
        'article_id' => 99,
        'title' => $article->title,
    ]);
});
