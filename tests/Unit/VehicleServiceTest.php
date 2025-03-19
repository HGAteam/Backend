<?php

namespace Tests\Unit;

use App\Services\Api\VehicleService;
use App\Services\Api\SwapiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VehicleServiceTest extends TestCase
{
    // Prueba que se obtengan vehículos correctamente desde la API.
    // Escenario: La API externa responde con datos válidos.
    // Finalidad: Verificar que el servicio procesa y devuelve los datos correctamente.
    public function test_fetches_vehicles_successfully()
    {
        Http::fake([
            'https://swapi.dev/api/vehicles?page=1' => Http::response([
                'count' => 82,
                'results' => [['name' => 'Sand Crawler']]
            ], 200)
        ]);

        $swapiService = new SwapiService();
        $service = new VehicleService($swapiService);
        $response = $service->getVehicles(10, 1);

        $this->assertNotEmpty($response);
    }

    // Prueba que se obtenga un vehículo por su ID correctamente.
    // Escenario: La API externa responde con datos válidos para un ID específico.
    // Finalidad: Verificar que el servicio devuelve los datos del vehículo solicitado.
    public function test_fetches_vehicle_by_id_successfully()
    {
        Http::fake([
            'https://swapi.dev/api/vehicles/1' => Http::response(['name' => 'Sand Crawler'], 200)
        ]);

        $swapiService = new SwapiService();
        $service = new VehicleService($swapiService);
        $response = $service->getVehicleById(1);

        $this->assertEquals('Sand Crawler', $response['name']);
    }
}
