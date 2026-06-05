<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rimuove la colonna `image` da `articles` e `categories`.
 *
 * Dopo il passaggio a Spatie Media Library le foto NON stanno più in una colonna
 * dedicata, ma nella tabella polimorfica `media`. Questa colonna era quindi rimasta
 * inutilizzata: la eliminiamo qui.
 *
 * NB: le vecchie migration add_image_to_articles / add_image_to_categories restano
 * intatte di proposito, così nella storia delle migration resta tracciato il prima/dopo.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     * Ricrea la colonna (nullable) così la migration è reversibile con migrate:rollback.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('image')->nullable();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable();
        });
    }
};
