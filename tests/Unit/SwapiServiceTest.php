<?php

namespace Tests\Unit;

use App\Services\Api\SwapiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SwapiServiceTest extends TestCase
{
    // Prueba que se obtengan datos correctamente desde la API SWAPI.
    // Escenario: La API externa responde con datos válidos.
    // Finalidad: Verificar que el servicio procesa y devuelve los datos correctamente.
    public function test_fetches_data_successfully()
    {
        Http::fake([
            'https://swapi.dev/api/people?page=1' => Http::response([
                'count' => 82,
                'results' => [['name' => 'Luke Skywalker']]
            ], 200)
        ]);

        $service = new SwapiService();
        $response = $service->fetchData('people');

        $this->assertEquals(82, $response['total']);
        $this->assertNotEmpty($response['results']);
    }

    // Prueba que el servicio maneje errores cuando la API SWAPI falla.
    // Escenario: La API externa responde con un error.
    // Finalidad: Verificar que el servicio devuelve un estado de error y maneja la excepción.
    public function test_returns_error_when_swapi_fails()
    {
        Http::fake([
            'https://swapi.dev/api/people?page=1' => Http::response([], 500)
        ]);

        $service = new SwapiService();
        $response = $service->fetchData('people');

        $this->assertEquals('error', $response['status']);
    }
}
