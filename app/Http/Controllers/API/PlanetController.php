<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Api\PlanetService;
use App\Utils\ApiResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * @OA\Tag(
 *     name="Planets",
 *     description="Endpoints relacionados con los planetas de Star Wars"
 * )
 */
class PlanetController extends Controller
{
    protected $planetService;

    /**
     * Constructor para inicializar el servicio de planetas.
     *
     * @param PlanetService $planetService Servicio que maneja la lógica de negocio relacionada con planetas.
     */
    public function __construct(PlanetService $planetService)
    {
        $this->planetService = $planetService;
    }
    
     /**
     * @group API | GET Planets
     * Obtener una lista de planetas
     *
     * Este endpoint devuelve una lista paginada de planetas del universo de Star Wars.
     *
     * @queryParam limit int Número de registros por página. Default: 10. Example: 5
     * @queryParam offset int Número de registros a omitir. Default: 0. Example: 10
     * @header Accept application/json
     * @header Authorization Bearer <TOKEN>
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Operación exitosa",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Tatooine",
     *       "rotation_period": "23",
     *       "orbital_period": "304",
     *       "diameter": "10465",
     *       "climate": "arid",
     *       "gravity": "1 standard",
     *       "terrain": "desert",
     *       "surface_water": "1",
     *       "population": "200000"
     *     }
     *   ],
     *   "pagination": {
     *     "total": 60,
     *     "limit": 10,
     *     "offset": 0,
     *     "next": "/api/planets?limit=10&offset=10",
     *     "previous": null
     *   }
     * }
     * @response 404 {
     *   "status": "error",
     *   "message": "No se encontraron planetas.",
     *   "errors": []
     * }
     * @response 500 {
     *   "status": "error",
     *   "message": "Error interno del servidor.",
     *   "error": "Detalles del error"
     * }
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Obtiene los parámetros de paginación (limit y offset) desde la solicitud.
            $limit = $request->query('limit', 10);
            $offset = $request->query('offset', 0);

            // Llama al servicio para obtener la lista de vehículos.
            $response = $this->planetService->getPlanets($limit, $offset, $request->bearerToken());

            if (is_null($response)) {
                return ApiResponseHandler::error('No se encontraron planetas.', 404);
            }

            return ApiResponseHandler::paginated($response['data'], 200, $response['pagination']);
        } catch (Throwable $e) {
            return ApiResponseHandler::error('Error interno del servidor.', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * @group API | GET Planets
     * Obtener los detalles de un planeta específico
     *
     * Este endpoint devuelve la información detallada de un planeta por su ID.
     *
     * @urlParam id int required El ID del planeta. Example: 1
     * @header Accept application/json
     * @header Authorization Bearer <TOKEN>
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Operación exitosa",
     *   "data": {
     *       "id": 1,
     *       "name": "Tatooine",
     *       "rotation_period": "23",
     *       "orbital_period": "304",
     *       "diameter": "10465",
     *       "climate": "arid",
     *       "gravity": "1 standard",
     *       "terrain": "desert",
     *       "surface_water": "1",
     *       "population": "200000"
     *   }
     * }
     * @response 404 {
     *   "status": "error",
     *   "message": "Planeta no encontrado.",
     *   "errors": []
     * }
     * @response 500 {
     *   "status": "error",
     *   "message": "Error interno del servidor.",
     *   "error": "Detalles del error"
     * }
     */
    public function show($id): JsonResponse
    {
        try {
            // Llama al servicio para obtener los detalles de un planeta por su ID.
            $response = $this->planetService->getPlanetById($id, request()->bearerToken());

             // Si la respuesta indica un error, devuelve un mensaje de error con código 404.
             if (is_null($response)) {
                return ApiResponseHandler::error('Planeta no encontrado.', 404);
            }

            // Devuelve los detalles del planeta en formato JSON.
            return ApiResponseHandler::success($response);
        } catch (Throwable $e) {
            return ApiResponseHandler::error('Error interno del servidor.', 500, ['exception' => $e->getMessage()]);
        }
    }
}
