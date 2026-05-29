<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabella pivot: nome singolare, in ordine alfabetico 
        Schema::create('article_tag', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');

            // primary key: la combinazione dei loro valori deve essere unica (ciò impedisce che lo stesso articolo possa essere associato più volte allo stesso tag)
            $table->primary(['article_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_tag');
    }
};
