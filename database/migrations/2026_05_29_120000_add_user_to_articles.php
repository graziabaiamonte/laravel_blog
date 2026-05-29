<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        //    cascadeOnDelete(): se un utente viene eliminato, anche i suoi articoli
        //    vengono eliminati automaticamente.
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Prima rimuovo il vincolo di chiave esterna, poi la colonna.
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
