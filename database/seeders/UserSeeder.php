<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->create();

        // Il MIO utente di sviluppo, sempre uguale, per loggarmi facilmente.
        // Se l'utente c'è già lo restituisce così com'è; se non c'è lo crea.
        // Posso rilanciare il seeder quante volte voglio senza errori
        // di "email duplicata" (cosa che invece succederebbe con create()).
        User::firstOrCreate(
            ['email' => 'grazia@gmail.com'],
            [
                'name' => 'Grazia',
                'password' => 'passw',
                'email_verified_at' => now(),
            ]
        );
    }
}
