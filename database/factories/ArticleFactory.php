<?php

namespace Database\Factories;

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
            // così di default ogni articolo ha la sua categoria.
            'category_id' => Category::factory(),

            // --- I campi di testo, generati con Faker ---

            // sentence() crea una frase di alcune parole
            'title' => fake()->sentence(),

            // paragraphs(3, true): genera 3 paragrafi di testo finto.
            // Il secondo argomento "true" fa restituire UNA stringa unica
            // (invece di un array di paragrafi)
            'content' => fake()->paragraphs(3, true),

            // 'image' => null, // VECCHIO sistema: la colonna non è più gestita (ora c'è la tabella `media`).
        ];
    }

    /**
     * STATE: articolo senza categoria.
     *
     * Uno "state" è una variante riutilizzabile della factory.
     * Restituisce solo i campi che vuoi SOVRASCRIVERE rispetto a definition().
     * Uso:  Article::factory()->withoutCategory()->create();
     *       -> crea un articolo identico agli altri, ma con category_id = null.
     */
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
