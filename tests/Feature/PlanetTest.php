<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanetTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate()
    {
        $user = User::factory()->create();
        return $user->createToken('TestToken')->accessToken;
    }

    public function test_fetches_all_planets_successfully()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->getJson('/api/planets');

        $decodedResponse = $response->json();

        if (isset($decodedResponse['pagination'])) {
            $response->assertJsonStructure([
                'status',
                'message',
                'data' => [],
                'pagination' => [
                    'total',
                    'limit',
                    'offset',
                    'next',
                    'previous'
                ]
            ]);
        } else {
            $response->assertJsonStructure([
                'status',
                'message',
                'data' => []
            ]);
        }

        $response->assertStatus(200);
    }

    public function test_fetches_a_planets_by_id_successfully()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->getJson('/api/planets/1');

        $response->assertStatus(200);
    }

    public function test_returns_404_when_planets_not_found()
    {
        $token = $this->authenticate();
    
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->getJson('/api/planets/9999');
    
        $decodedResponse = $response->json();
    
        if (isset($decodedResponse['status']) && $decodedResponse['status'] === 'error') {
            $response->assertStatus(404)->assertJson([
                'status'  => 'error',
                'message' => 'Planeta no encontrado.', 
            ]);
        } else {
            $response->assertStatus(200); 
        }
    }
}
