<?php

// ArticleController, metodo store().
// Qui simuliamo una richiesta HTTP VERA che attraversa: rotta -> middleware
// auth -> StoreArticleRequest (validazione) -> controller -> database -> evento

use App\Enums\ArticleStatus;
use App\Models\User;
use App\Notifications\ArticleCreated as ArticleCreatedNotification;
use Illuminate\Support\Facades\Notification;

// un utente autenticato crea un articolo -> salvato come BOZZA e notifica inviata.
test('un utente autenticato può creare un articolo e riceve la notifica', function () {
    Notification::fake();

    $user = User::factory()->create();

    // actingAs($user): le richieste successive partono come se fosse loggato.
    // post(...) invia i dati del form alla rotta admin.articles.store.
    $response = $this->actingAs($user)->post(route('admin.articles.store'), [
        'title'   => 'Mio primo articolo di test',
        'content' => 'Questo è il contenuto di prova del mio articolo.',
    ]);

    // Il controller, dopo aver salvato, fa redirect verso la dashboard.
    $response->assertRedirect(route('admin.dashboard'));

    // L'articolo deve esistere nel DB, appartenere all'utente ed essere in BOZZA
    $this->assertDatabaseHas('articles', [
        'title'   => 'Mio primo articolo di test',
        'user_id' => $user->id,
        'status'  => ArticleStatus::Draft->value,
    ]);

    // Verifichiamo che la notifica sia stata inviata PROPRIO a quell'utente.
    Notification::assertSentTo($user, ArticleCreatedNotification::class);
});

// senza login non si può creare un articolo (middleware 'auth')
test('un utente non autenticato viene rimandato al login', function () {
    // Nessun actingAs: siamo "ospiti".
    $response = $this->post(route('admin.articles.store'), [
        'title'   => 'Articolo non consentito',
        'content' => 'Contenuto qualsiasi.',
    ]);

    // Il middleware auth blocca e reindirizza alla pagina di login.
    $response->assertRedirect(route('login'));
});

// la validazione blocca un titolo mancante (regola 'required' nello StoreArticleRequest).
test('lo store fallisce se manca il titolo', function () {
    Notification::fake();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('admin.articles.store'), [
        // 'title' assente di proposito
        'content' => 'Contenuto senza titolo.',
    ]);

    // La validazione fallita rimanda indietro con un errore sul campo 'title'.
    $response->assertSessionHasErrors('title');

    // Nessun articolo deve essere stato creato...
    $this->assertDatabaseCount('articles', 0);

    // ...e nessuna notifica deve essere partita.
    Notification::assertNothingSent();
});
