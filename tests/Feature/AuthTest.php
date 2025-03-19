<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // Prueba que un usuario pueda registrarse correctamente.
    // Escenario: Se envían datos válidos para el registro.
    // Finalidad: Verificar que el registro crea un usuario en la base de datos y devuelve la respuesta esperada.
    public function test_registers_a_user()
    {
        $response = $this->postJson('/api/register', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'user',
                'note',
            ])
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    // Prueba que un usuario pueda iniciar sesión correctamente.
    // Escenario: Se envían credenciales válidas.
    // Finalidad: Verificar que el inicio de sesión devuelve un token y la respuesta esperada.
    public function test_logs_in_a_user()
    {
        User::factory()->create([
            'email'    => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'user',
                'token',
                'note',
            ])
            ->assertJsonPath('status', 'success');

        $this->assertNotEmpty($response->json('token'));
    }

    // Prueba que el inicio de sesión falle con credenciales incorrectas.
    // Escenario: Se envían credenciales inválidas.
    // Finalidad: Verificar que el sistema devuelve un error con el mensaje adecuado.
    public function test_fails_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status'  => 'error',
                'message' => 'Credenciales incorrectas.',
            ]);
    }

    // Prueba que un usuario pueda cerrar sesión correctamente.
    // Escenario: Un usuario autenticado solicita cerrar sesión.
    // Finalidad: Verificar que el token se invalida y se devuelve la respuesta esperada.
    public function test_logout_a_user()
    {
        $user = User::factory()->create([
            'email'    => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        Passport::actingAs($user);
        $token = $user->createToken('TestToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status'  => 'success',
                'message' => 'Sesión cerrada correctamente.',
            ]);
    }
}
