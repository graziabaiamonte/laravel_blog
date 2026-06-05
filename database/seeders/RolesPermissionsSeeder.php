<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
// I modelli di Spatie si chiamano anch'essi Role/Permission: per evitare il
// conflitto di nome con i NOSTRI enum, qui li importiamo con un alias.
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role as SpatieRole;
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
        // Creo nel DB un record per OGNI caso dell'enum Permission.
        foreach (Permission::cases() as $permission) {
            SpatiePermission::firstOrCreate(['name' => $permission->value]);
        }

        // --- 2. I RUOLI + collegamento dei permessi ---
        foreach (Role::cases() as $role) {
            $spatieRole = SpatieRole::firstOrCreate(['name' => $role->value]);

            $spatieRole->syncPermissions(
                array_map(fn (Permission $p) => $p->value, $role->permissions())
            );
        }

        // --- 3. ASSEGNO il ruolo admin AL MIO UTENTE DI SVILUPPO ---
        $dev = User::whereEmail('grazia@gmail.com')->first();
        $dev?->assignRole(Role::Admin->value);
    }
}
