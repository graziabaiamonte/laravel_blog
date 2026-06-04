<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // spatie tiene in cache ruoli e permessi, quindi la svuota
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // --- 1. I PERMESSI ---
        // firstOrCreate evita duplicati se rilancio il seeder.
        $publish = Permission::firstOrCreate(['name' => 'publish articles']);
        $manage  = Permission::firstOrCreate(['name' => 'manage articles']);

        // --- 2. I RUOLI ---
        $admin  = Role::firstOrCreate(['name' => 'admin']);
        $author = Role::firstOrCreate(['name' => 'author']);

        // --- 3. COLLEGO I PERMESSI AI RUOLI ---
        $admin->syncPermissions([$publish, $manage]);
        $author->syncPermissions([$publish]);

        // --- 4. ASSEGNO IL RUOLO admin AL MIO UTENTE DI SVILUPPO ---
        $dev = User::whereEmail('grazia@gmail.com')->first();
        $dev?->assignRole($admin);
    }
}
