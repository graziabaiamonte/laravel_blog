<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('app:delete-empty-categories')]
#[Description('Elimina le categorie senza articoli associati')]
class DeleteEmptyCategories extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $categorieVuote = Category::doesntHave('articles')->get();

        if ($categorieVuote->isEmpty()) {
            Log::channel('deleted-categories')->info('Nessuna categoria è stata eliminata.');
            $this->info('Nessuna categoria da eliminare.');

            return;
        }

        foreach ($categorieVuote as $categoria) {
            $nome = $categoria->name;
            $categoria->delete();

            Log::channel('deleted-categories')->info("Categoria eliminata: {$nome}");
            $this->info("Categoria eliminata: {$nome}");
        }
    }
}
