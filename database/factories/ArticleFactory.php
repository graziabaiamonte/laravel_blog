<?php

namespace Database\Factories;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // --- Le due relazioni "belongsTo" ---

            // user_id: nella tua migration è OBBLIGATORIO (nullable(false)).
            // Passando User::factory() come valore, Laravel capisce che,
            // se non gli forniamo un utente esistente, deve CREARNE UNO NUOVO al volo e usarne l'id. Questo si chiama "factory relationship".
            'user_id' => User::factory(),

            // category_id: nella migration è nullable (un articolo può non avere categoria).
            // Qui scegliamo comunque di assegnargliene una creandola
            'category_id' => Category::factory(),

            'title' => fake()->sentence(),

            // paragraphs(3, true): genera 3 paragrafi di testo finto.
            // Il secondo argomento "true" fa restituire UNA stringa unica
            // (invece di un array di paragrafi)
            'content' => fake()->paragraphs(3, true),

            'status' => ArticleStatus::Published->value,

            // 'image' => null, // VECCHIO sistema: la colonna non è più gestita (ora c'è la tabella `media`).
        ];
    }

    /**
     * STATE: articolo in BOZZA.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Draft->value,
        ]);
    }

    public function withoutCategory(): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => null,
        ]);
    }

    /**
     * STATE: articolo assegnato a un utente specifico.
     *
     * questo state accetta come parametro l'utente di cui vogliamo che l'articolo sia.
     * Uso:  Article::factory()->forUser($mario)->create();
     *       -> usa l'id di $mario invece di crearne uno nuovo.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
