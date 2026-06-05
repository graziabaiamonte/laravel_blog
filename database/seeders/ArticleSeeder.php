<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recuperiamo dal DB gli utenti, le categorie e i tag già creati
        // dai seeder eseguiti prima di questo (vedi DatabaseSeeder).
        $users = User::all();
        $categories = Category::all();
        $tags = Tag::all();

        // Creiamo 20 articoli.
        Article::factory(20)
            // recycle() non crea a caso User o Category  ma pesca a caso da queste collezioni esistenti
            ->recycle($users)
            ->recycle($categories)
            ->create()
            ->each(function (Article $article) use ($tags) {
                // Per ogni articolo scegliamo da 1 a 3 tag a caso...
                $tagIdsCasuali = $tags->random(rand(1, 3))->pluck('id')->toArray();

                // attach() inserisce le righe nella tabella pivot article_tag
                $article->tags()->attach($tagIdsCasuali);
            });

        // 3 articoli senza categoria, usando lo state 
        Article::factory(3)
            ->recycle($users)
            ->withoutCategory()
            ->create();

        // 5 articoli in BOZZA: NON appariranno sulla home pubblica,
        // ma li vedremo nella dashboard con il badge "Bozza".
        Article::factory(5)
            ->recycle($users)
            ->recycle($categories)
            ->draft()
            ->create();
    }
}
