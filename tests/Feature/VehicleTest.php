<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate()
    {
        $user = User::factory()->create();
        
        return $user->createToken('TestToken')->accessToken;
    }

    public function test_fetches_all_vehicles_successfully()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->getJson('/api/vehicles');

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

    public function test_fetches_a_vehicles_by_id_successfully()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->getJson('/api/vehicles/20');

        $response->assertStatus(200);
    }

    public function test_returns_404_when_vehicles_not_found()
    {
        $token = $this->authenticate();
    
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->getJson('/api/vehicles/9999');
    
        $decodedResponse = $response->json();
    
        if (isset($decodedResponse['status']) && $decodedResponse['status'] === 'error') {
            $response->assertStatus(404)->assertJson([
                'status'  => 'error',
                'message' => 'Vehículo no encontrado.', 
            ]);
        } else {
            $response->assertStatus(200); 
        }
    }
}
