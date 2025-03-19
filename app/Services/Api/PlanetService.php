<?php

namespace App\Services\Api;

use App\Services\Api\SwapiService;
use App\Http\Resources\SWAPI\PlanetResource;
use App\Utils\ApiResponseHandler;

class PlanetService
{
    protected $swapiService;

    public function __construct(SwapiService $swapiService)
    {
        $this->swapiService = $swapiService;
    }

    public function getPlanets(int $limit = 10, int $offset = 0, string $accessToken = null)
    {
        $response = $this->swapiService->fetchData('planets', $limit, $offset, $accessToken);

        if (!$response || !isset($response['results'])) {
            return null;
        }

        return [
            'data'       => PlanetResource::collection(collect($response['results'])),
            'pagination' => [
                'total'    => $response['total'],
                'limit'    => $limit,
                'offset'   => $offset,
                'next'     => $offset + $limit < $response['total'] ? route('planets.index', ['limit' => $limit, 'offset' => $offset + $limit]) : null,
                'previous' => $offset > 0 ? route('planets.index', ['limit' => $limit, 'offset' => max($offset - $limit, 0)]) : null,
            ],
        ];
    }

    public function getPlanetById(int $id, string $accessToken = null)
    {
        $response = $this->swapiService->getById('planets', $id, $accessToken);
       
        if (!$response || empty($response)) {
            return null;
        }

        return new PlanetResource($response);
    }
}
