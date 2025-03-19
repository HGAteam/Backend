<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Api\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    // Prueba que un usuario pueda registrarse correctamente usando el servicio.
    // Escenario: Se envían datos válidos para el registro.
    // Finalidad: Verificar que el servicio crea un usuario y devuelve la respuesta esperada.
    public function test_registers_a_user()
    {
        $authService = app(AuthService::class);

        $data = [
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => 'password',
        ];

        $response = $authService->register($data);

        // Verifica que el usuario se ha creado en la base de datos
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);

        // Verifica la estructura de respuesta
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('user', $response);
        $this->assertArrayHasKey('note', $response);
    }

    // Prueba que un usuario pueda iniciar sesión correctamente usando el servicio.
    // Escenario: Se envían credenciales válidas.
    // Finalidad: Verificar que el servicio devuelve un token y la respuesta esperada.
    public function test_logs_in_a_user()
    {
        $authService = app(AuthService::class);

        // Crear un usuario en la base de datos con contraseña encriptada
        $user = User::factory()->create([
            'email'    => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $credentials = [
            'email'    => 'john@example.com',
            'password' => 'password',
        ];

        $response = $authService->login($credentials);

        // Verifica que la respuesta es un array
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);

        // Verifica que la respuesta contiene un token
        $this->assertArrayHasKey('token', $response);
        $this->assertNotEmpty($response['token']);
    }

    // Prueba que el inicio de sesión falle con credenciales incorrectas usando el servicio.
    // Escenario: Se envían credenciales inválidas.
    // Finalidad: Verificar que el servicio devuelve un error con el mensaje adecuado.
    public function test_fails_login_with_invalid_credentials()
    {
        $authService = app(AuthService::class);
        
        $credentials = [
            'email'    => 'fake@example.com',
            'password' => 'wrongpassword',
        ];
        
        $response = $authService->login($credentials);

        // Verifica que la respuesta es un array
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('error', $response['status']);

        // Verifica el mensaje de error
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Credenciales incorrectas.', $response['message']);
    }

    // Prueba que un usuario pueda cerrar sesión correctamente usando el servicio.
    // Escenario: Un usuario autenticado solicita cerrar sesión.
    // Finalidad: Verificar que el servicio invalida el token y devuelve la respuesta esperada.
    public function test_logout_a_user()
    {
        $authService = app(AuthService::class);

        // Crear usuario con contraseña encriptada
        $user = User::factory()->create([
            'email'    => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        // Simular autenticación con Laravel Passport
        Passport::actingAs($user);

        // Generar un token manualmente para el usuario
        $token = $user->createToken('TestToken')->accessToken;

        // Cerrar sesión usando el servicio
        $logoutResponse = $authService->logout($user);

        // Verificar que la respuesta es un array con estado correcto
        $this->assertIsArray($logoutResponse);
        $this->assertArrayHasKey('status', $logoutResponse);
        $this->assertEquals('success', $logoutResponse['status']);

        // Verificar que el token ha sido revocado
        $this->assertDatabaseMissing('oauth_access_tokens', [
            'user_id' => $user->id,
            'revoked' => false,
        ]);
    }
}
