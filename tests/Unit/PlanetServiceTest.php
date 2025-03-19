<?php

namespace Tests\Unit;

use App\Services\Api\PlanetService;
use App\Services\Api\SwapiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PlanetServiceTest extends TestCase
{
    // Prueba que se obtengan planetas correctamente desde la API.
    // Escenario: La API externa responde con datos válidos.
    // Finalidad: Verificar que el servicio procesa y devuelve los datos correctamente.
    public function test_fetches_planets_successfully()
    {
        Http::fake([
            'https://swapi.dev/api/planets?page=1' => Http::response([
                'count' => 82,
                'results' => [['name' => 'Tatooine']]
            ], 200)
        ]);

        $swapiService = new SwapiService();
        $service = new PlanetService($swapiService);
        $response = $service->getPlanets(10, 1);

        $this->assertNotEmpty($response);
    }

    // Prueba que se obtenga un planeta por su ID correctamente.
    // Escenario: La API externa responde con datos válidos para un ID específico.
    // Finalidad: Verificar que el servicio devuelve los datos del planeta solicitado.
    public function test_fetches_planet_by_id_successfully()
    {
        Http::fake([
            'https://swapi.dev/api/planets/1' => Http::response(['name' => 'Tatooine'], 200)
        ]);

        $swapiService = new SwapiService();
        $service = new PlanetService($swapiService);
        $response = $service->getPlanetById(1);

        $this->assertEquals('Tatooine', $response['name']);
    }
}
