<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

// nome con con il quale lancio il command
#[Signature('app:scrivi-log')] 

#[Description('Scrive un messaggio nel file di log dedicato')]

class ScriviLog extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Scrive nel canale di log "scrivi-log" (config/logging.php)
        Log::channel('scrivi-log')->info('ciao, il tuo command sta funzionando ogni due minuti');

        // Messaggio nel terminale quando lancio il command a mano con php artisan app:scrivi-log
        $this->info('Messaggio scritto nel log.');
    }
}
