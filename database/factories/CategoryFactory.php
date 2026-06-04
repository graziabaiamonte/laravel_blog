<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * La factory deve "sapere" quale modello costruisce.
 * Lo dichiariamo qui sotto (e lo ripetiamo nella PHPDoc @extends per l'autocompletamento).
 *
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    // Collega esplicitamente questa factory al modello Category.
    protected $model = Category::class;

    /**
     * definition() restituisce un array associativo:
     * "colonna del DB" => "valore da inserire".
     * Ogni volta che chiami Category::factory()->create(),
     * Laravel esegue questo metodo per generare UNA categoria.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // fake() è l'helper di Faker, la libreria che genera dati finti.
            // ->unique() garantisce che ogni categoria abbia una parola diversa
            'name' => fake()->unique()->word(),

            'image' => null,
        ];
    }
}
