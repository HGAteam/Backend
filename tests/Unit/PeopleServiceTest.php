<?php

namespace Tests\Unit;

use App\Services\Api\PeopleService;
use App\Services\Api\SwapiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PeopleServiceTest extends TestCase
{
    // Prueba que se obtengan personas correctamente desde la API.
    // Escenario: La API externa responde con datos válidos.
    // Finalidad: Verificar que el servicio procesa y devuelve los datos correctamente.
    public function test_fetches_people_successfully()
    {
        Http::fake([
            'https://swapi.dev/api/people?page=1' => Http::response([
                'count' => 82,
                'results' => [['name' => 'Luke Skywalker']]
            ], 200)
        ]);

        $swapiService = new SwapiService();
        $service = new PeopleService($swapiService);
        $response = $service->getPeople(10, 1);

        $this->assertNotEmpty($response);
    }

    // Prueba que se obtenga una persona por su ID correctamente.
    // Escenario: La API externa responde con datos válidos para un ID específico.
    // Finalidad: Verificar que el servicio devuelve los datos de la persona solicitada.
    public function test_fetches_person_by_id_successfully()
    {
        Http::fake([
            'https://swapi.dev/api/people/1' => Http::response(['name' => 'Luke Skywalker'], 200)
        ]);

        $swapiService = new SwapiService();
        $service = new PeopleService($swapiService);
        $response = $service->getPersonById(1);

        $this->assertEquals('Luke Skywalker', $response['name']);
    }
}
