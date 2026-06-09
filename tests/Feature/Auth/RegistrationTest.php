<?php

// importo il seeder poichè nel db di test i ruoli non esistono, quindi il seeder va lanciato prima di $response = $this->post nel secondo test
use Database\Seeders\RolesPermissionsSeeder;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\seed;

test('registration screen can be rendered', function () {
    $response = get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    seed(RolesPermissionsSeeder::class);

    $response = post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});
