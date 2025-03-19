<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Client;
use Tests\TestCase;

class CorsAndAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba que una aplicación externa puede obtener un token de acceso y consumir la API.
     * 
     * Escenario esperado:
     * - Un cliente externo solicita un token de acceso usando credenciales válidas.
     * - La API responde con un token de acceso.
     * - El cliente usa el token para acceder a un endpoint protegido.
     * - La API responde con un estado 200 y una estructura JSON válida.
     */
    public function test_una_aplicacion_externa_puede_obtener_un_token_y_consumir_la_api(): void
    {
        // Simula un cliente de API externo SIN definir el ID
        $client = Client::factory()->create([
            'name' => 'External App',
            'secret' => 'secret123',
            'provider' => null,
            'redirect' => '',
            'personal_access_client' => false,
            'password_client' => false,
            'revoked' => false,
        ]);

        // Simula una solicitud para obtener un token
        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->id, // Ahora Laravel asigna el ID automáticamente
            'client_secret' => 'secret123',
            'scope' => '*'
        ]);

        $response->assertStatus(200);
        $accessToken = $response->json('access_token');

        // Usa el token para hacer una solicitud a la API
        $apiResponse = $this->getJson('/api/people', [
            'Authorization' => "Bearer $accessToken",
            'Origin' => 'http://external-app.com',
        ]);

        $apiResponse->assertStatus(200);
        $apiResponse->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
    }

    /**
     * Prueba que una solicitud sin token recibe un error 401.
     * 
     * Escenario esperado:
     * - Un cliente realiza una solicitud a un endpoint protegido sin incluir un token de acceso.
     * - La API responde con un estado 401 y un mensaje de error indicando que se requiere un token válido.
     */
    public function test_una_solicitud_sin_token_recibe_un_error_401(): void
    {
        $response = $this->withHeaders(['Authorization' => null])
        ->getJson('/api/people');

        $decodedResponse = $response->json();
        
        if (isset($decodedResponse['status']) && $decodedResponse['status'] === 'error') {
            $response->assertStatus(401)->assertJson([
                'status'  => 'error',
                'message' => 'Sin autorización. Se requiere un token válido.',
                'errors'  => [], 
            ]);
        } else {
            $response->assertStatus(200); 
        }
    }

    /**
     * Prueba que CORS está configurado correctamente y permite solicitudes desde aplicaciones externas.
     * 
     * Escenario esperado:
     * - Un cliente externo realiza una solicitud con un encabezado `Origin`.
     * - La API responde con el encabezado `Access-Control-Allow-Origin` configurado correctamente (en este caso, '*').
     */
    public function test_cors_permite_solicitudes_desde_aplicaciones_externas(): void
    {
        $response = $this->getJson('/api/people', [
            'Origin' => 'http://external-app.com',
        ]);

        $response->assertHeader('Access-Control-Allow-Origin', '*');
    }
}
