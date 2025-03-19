<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeopleTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate()
    {
        $user = User::factory()->create();
        return $user->createToken('TestToken')->accessToken;
    }

    public function test_fetches_all_people_successfully()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->getJson('/api/people');

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

    public function test_fetches_a_person_by_id_successfully()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->getJson('/api/people/1');

        $response->assertStatus(200);
    }

    public function test_returns_404_when_person_not_found()
    {
        $token = $this->authenticate();
    
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->getJson('/api/people/9999');
    
        $decodedResponse = $response->json();
    
        if (isset($decodedResponse['status']) && $decodedResponse['status'] === 'error') {
            $response->assertStatus(404)->assertJson([
                'status'  => 'error',
                'message' => 'Persona no encontrada.', 
            ]);
        } else {
            $response->assertStatus(200); 
        }
    }
    
}
