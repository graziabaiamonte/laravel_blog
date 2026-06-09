<?php

// ArticleController, metodo store().
// Qui simuliamo una richiesta HTTP VERA che attraversa: rotta -> middleware
// auth -> StoreArticleRequest (validazione) -> controller -> database -> evento

// use Tests\TestCase;
use App\Enums\ArticleStatus;
use App\Models\User;
use App\Notifications\ArticleCreated as ArticleCreatedNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

// un utente autenticato crea un articolo -> salvato come BOZZA e notifica inviata.
test('un utente autenticato può creare un articolo e riceve la notifica', function () {
    Notification::fake();

    $user = User::factory()->create();

    // actingAs($user): le richieste successive partono come se fosse loggato.
    $response = actingAs($user)->post(route('admin.articles.store'), [
        'title' => 'Mio primo articolo di test',
        'content' => 'Questo è il contenuto di prova del mio articolo.',
    ]);

    $response->assertRedirect(route('admin.dashboard'));

    assertDatabaseHas('articles', [
        'title' => 'Mio primo articolo di test',
        'user_id' => $user->id,
        'status' => ArticleStatus::Draft->value,
    ]);

    // Verifichiamo che la notifica sia stata inviata PROPRIO a quell'utente.
    Notification::assertSentTo($user, ArticleCreatedNotification::class);
});

// senza login non si può creare un articolo (middleware 'auth')
test('un utente non autenticato viene rimandato al login', function () {

    $response = post(route('admin.articles.store'), [
        'title' => 'Articolo non consentito',
        'content' => 'Contenuto qualsiasi.',
    ]);

    $response->assertRedirect(route('login'));
});

// la validazione blocca un titolo mancante (regola 'required' nello StoreArticleRequest).
test('lo store fallisce se manca il titolo', function () {
    Notification::fake();
    $user = User::factory()->create();
    $response = actingAs($user)->post(route('admin.articles.store'), [
        // 'title'
        'content' => 'Contenuto senza titolo.',
    ]);

    // La validazione fallita rimanda indietro con un errore sul campo 'title'.
    $response->assertSessionHasErrors('title');

    // Nessun articolo deve essere stato creato...
    assertDatabaseCount('articles', 0);

    // ...e nessuna notifica deve essere partita.
    Notification::assertNothingSent();
});
