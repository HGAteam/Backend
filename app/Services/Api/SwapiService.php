<?php
namespace App\Services\Api;

use Exception;
use Illuminate\Support\Facades\Http;

class SwapiService
{
    // URL base de la API de SWAPI
    protected string $swapiUrl = 'https://swapi.dev/api';

    /**
     * Obtiene datos de un endpoint de SWAPI con soporte de paginación.
     *
     * @param string $endpoint Endpoint de SWAPI al que se desea acceder.
     * @param int $limit Número máximo de resultados a devolver.
     * @param int $offset Desplazamiento para la paginación.
     * @param string|null $accessToken Token de acceso opcional para autenticación.
     * @return array Resultado de la consulta con datos paginados o un mensaje de error.
     */
    public function fetchData(string $endpoint, int $limit = 10, int $offset = 0, $accessToken = null)
    {
        try {
            // Calcular la página correcta basada en el offset (SWAPI usa un límite fijo de 10 por página)
            $page     = intval($offset / 10) + 1; 
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get("{$this->swapiUrl}/$endpoint", ['page' => $page]);

            // Verificar si la solicitud falló
            if ($response->failed()) {
                return [
                    'status'  => 'error',
                    'message' => "Error al conectar con SWAPI",
                ];
            }

            $data = $response->json();

            // Verificar si no se encontraron resultados
            if (! isset($data['results']) || empty($data['results'])) {
                return [
                    'status'  => 'error',
                    'message' => "No se encontraron datos en SWAPI.",
                ];
            }

            // Aplicar offset y limit dentro de la página obtenida
            $adjustedOffset = $offset % 10;
            $results        = array_slice($data['results'], $adjustedOffset, $limit);

            // Retornar los datos paginados junto con información adicional
            return [
                'results'  => $results,
                'total'    => $data['count'] ?? 0,
                'limit'    => $limit,
                'offset'   => $offset,
                'next'     => $offset + $limit < $data['count'] ? url("/api/$endpoint?limit=$limit&offset=" . ($offset + $limit)) : '#',
                'previous' => $offset > 0 ? url("/api/$endpoint?limit=$limit&offset=" . max($offset - $limit, 0)) : '#',
            ];
        } catch (Exception $e) {
            // Manejar errores y retornar un mensaje descriptivo
            return [
                'status'  => 'error',
                'message' => "Error al obtener los datos: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Obtiene un recurso específico por su ID desde un endpoint de SWAPI.
     *
     * @param string $endpoint Endpoint de SWAPI al que se desea acceder.
     * @param int $id ID del recurso a obtener.
     * @param string|null $accessToken Token de acceso opcional para autenticación.
     * @return array Resultado de la consulta con los datos del recurso o un mensaje de error.
     */
    public function getById(string $endpoint, int $id, $accessToken = null)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get("{$this->swapiUrl}/$endpoint/$id");

            // Verificar si la solicitud falló
            if ($response->failed()) {
                return [
                    'status'  => 'error',
                    'message' => 'No se pudo encontrar el recurso.',
                ];
            }

            // Retornar los datos del recurso
            return $response->json();
        } catch (Exception $e) {
            // Manejar errores y retornar un mensaje descriptivo
            return [
                'status'  => 'error',
                'message' => "Error al obtener el recurso: " . $e->getMessage(),
            ];
        }
    }
}
