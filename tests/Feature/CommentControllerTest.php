<?php

use App\Models\Article;
use App\Models\User;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

test('un utente loggato può commentare via AJAX e l\'autore riceve la notifica', function () {
    Notification::fake();

    $owner = User::factory()->create();

    $article = Article::factory()->create(['user_id' => $owner->id]);

    $commenter = User::factory()->createOne();

    $response = actingAs($commenter)->postJson(
        route('comments.store', $article),
        ['body' => 'Bel articolo, complimenti!']
    );

    $response->assertOk()
        ->assertJson(['success' => true])
        ->assertJsonPath('comment.author', $commenter->name)
        ->assertJsonPath('comment.isPending', true);

    assertDatabaseHas('comments', [
        'article_id' => $article->id,
        'user_id' => $commenter->id,
        'status' => 'pending',
    ]);

    Notification::assertSentTo($owner, NewCommentNotification::class);
});

// La validazione AJAX blocca un commento vuoto: risposta 422 con errore su 'body'.
test('un commento vuoto restituisce 422', function () {
    Notification::fake();

    $article = Article::factory()->create();
    $commenter = User::factory()->create();

    $response = actingAs($commenter)->postJson(
        route('comments.store', $article),
        ['body' => '']
    );

    $response->assertStatus(422)
        ->assertJsonValidationErrors('body');

    assertDatabaseCount('comments', 0);
    Notification::assertNothingSent();
});

// Un visitatore non loggato non può commentare (middleware 'auth').
test('un utente non autenticato non può commentare', function () {
    $article = Article::factory()->create();

    $response = post(route('comments.store', $article), ['body' => 'Ciao']);

    $response->assertRedirect(route('login'));
    assertDatabaseCount('comments', 0);
});
