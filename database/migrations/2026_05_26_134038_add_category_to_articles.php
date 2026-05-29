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

            // Per convenzione, il nome category_id indica a Laravel che questa colonna punta alla tabella categories.
            $table->foreignId('category_id')->after('content')->nullable()->constrained()->onDelete('set null');

            // ->constrained():  attiva il vincolo di integrità referenziale. Laravel capisce automaticamente che deve collegarsi alla colonna id della tabella categories. Quindi il valore di category_id deve essere per forza uguale a quello di id della tabella categories
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
