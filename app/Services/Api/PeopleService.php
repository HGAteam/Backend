<?php
namespace App\Services\Api;

use App\Http\Resources\SWAPI\PeopleResource;
use App\Services\Api\SwapiService;
use App\Utils\ApiResponseHandler;

class PeopleService
{
    protected $swapiService;

    public function __construct(SwapiService $swapiService)
    {
        $this->swapiService = $swapiService;
    }

    /**
     * Obtiene una lista de personas desde la API SWAPI.
     *
     * @param int $limit Número máximo de resultados a devolver.
     * @param int $offset Desplazamiento para la paginación.
     * @param string|null $accessToken Token de acceso opcional para la autenticación.
     * @return array|null Respuesta con los datos de las personas y la información de paginación.
     */
    public function getPeople(int $limit = 10, int $offset = 0, string $accessToken = null)
    {
        $response = $this->swapiService->fetchData('people', $limit, $offset, $accessToken);

        if (!$response || !isset($response['results'])) {
            return null;
        }

        return [
            'data'       => PeopleResource::collection(collect($response['results'])),
            'pagination' => [
                'total'    => $response['total'],
                'limit'    => $limit,
                'offset'   => $offset,
                'next'     => $offset + $limit < $response['total'] ? route('people.index', ['limit' => $limit, 'offset' => $offset + $limit]) : null,
                'previous' => $offset > 0 ? route('people.index', ['limit' => $limit, 'offset' => max($offset - $limit, 0)]) : null,
            ],
        ];
    }

    /**
     * Obtiene los detalles de una persona específica por su ID.
     *
     * @param int $id ID de la persona a buscar.
     * @param string|null $accessToken Token de acceso opcional para la autenticación.
     * @return PeopleResource|null Respuesta con los datos de la persona o null si no se encuentra.
     */
    public function getPersonById(int $id, string $accessToken = null)
    {
        $response = $this->swapiService->getById('people', $id, $accessToken);

        if (!$response || empty($response)) {
            return null;
        }

        return new PeopleResource($response);
    }
}
