<?php

// importo il seeder poichè nel db di test i ruoli non esistono, quindi il seeder va lanciato prima di $response = $this->post nel secondo test
use Database\Seeders\RolesPermissionsSeeder;
use function Pest\Laravel\seed;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    seed(RolesPermissionsSeeder::class); 

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});
