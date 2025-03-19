<?php

namespace Tests;

use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase; // Si prefieres, usa DatabaseMigrations

    protected function setUp(): void
    {
        parent::setUp();

        // Migrar y sembrar la base de datos antes de cada prueba
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed --class=UserSeeder');
        Artisan::call('db:seed --class=PassportSeeder');

        // Crear y autenticar un usuario para las pruebas
        $user = \App\Models\User::factory()->create();
        Passport::actingAs($user);
    }
}
