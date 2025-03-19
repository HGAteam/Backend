<?php

namespace App\Services\Api;

use App\Services\Api\SwapiService;
use App\Http\Resources\SWAPI\VehicleResource;
use App\Utils\ApiResponseHandler;

class VehicleService
{
    protected $swapiService;

    public function __construct(SwapiService $swapiService)
    {
        $this->swapiService = $swapiService;
    }

    /**
     * Obtiene una lista de vehículos desde la API SWAPI.
     *
     * @param int $limit Número máximo de resultados a devolver.
     * @param int $offset Desplazamiento para la paginación.
     * @param string|null $accessToken Token de acceso opcional para la autenticación.
     * @return array|null Respuesta con los datos de los vehículos y la información de paginación.
     */
    public function getVehicles(int $limit = 10, int $offset = 0, string $accessToken = null)
    {
        $response = $this->swapiService->fetchData('vehicles', $limit, $offset, $accessToken);

        if (!$response || !isset($response['results'])) {
            return null;
        }

        return [
            'data'       => VehicleResource::collection(collect($response['results'])),
            'pagination' => [
                'total'    => $response['total'],
                'limit'    => $limit,
                'offset'   => $offset,
                'next'     => $offset + $limit < $response['total'] ? route('vehicles.index', ['limit' => $limit, 'offset' => $offset + $limit]) : null,
                'previous' => $offset > 0 ? route('vehicles.index', ['limit' => $limit, 'offset' => max($offset - $limit, 0)]) : null,
            ],
        ];
    }

    /**
     * Obtiene los detalles de un vehículo específico por su ID.
     *
     * @param int $id ID del vehículo a buscar.
     * @param string|null $accessToken Token de acceso opcional para la autenticación.
     * @return VehicleResource|null Respuesta con los datos del vehículo o null si no se encuentra.
     */
    public function getVehicleById(int $id, string $accessToken = null)
    {
        $response = $this->swapiService->getById('vehicles', $id, $accessToken);
    
        if (!$response || empty($response)) {
            return null;
        }

        return new VehicleResource($response);
    }
}
