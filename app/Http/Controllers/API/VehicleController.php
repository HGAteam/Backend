<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Api\VehicleService;
use App\Utils\ApiResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * @OA\Tag(
 *     name="Vehicles",
 *     description="Endpoints relacionados con los vehículos de Star Wars"
 * )
 */
class VehicleController extends Controller
{
    protected $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    /**
     * @group API | GET Vehicles
     * Obtener una lista de vehículos
     *
     * Este endpoint devuelve una lista paginada de vehículos de Star Wars.
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
     *       "name": "Speeder Bike",
     *       "model": "74-Z speeder bike",
     *       "manufacturer": "Aratech Repulsorlift Company",
     *       "cost_in_credits": "5000",
     *       "length": "3.4",
     *       "max_atmosphering_speed": "200",
     *       "crew": "1",
     *       "passengers": "0",
     *       "cargo_capacity": "4",
     *       "consumables": "None",
     *       "vehicle_class": "speeder bike"
     *     }
     *   ],
     *   "pagination": {
     *     "total": 82,
     *     "limit": 10,
     *     "offset": 0,
     *     "next": "/api/vehicles?limit=10&offset=10",
     *     "previous": null
     *   }
     * }
     * @response 404 {
     *   "status": "error",
     *   "message": "No se encontraron vehículos.",
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
            $limit  = $request->query('limit', 10);
            $offset = $request->query('offset', 0);

            // Llama al servicio para obtener la lista de vehículos.
            $response = $this->vehicleService->getVehicles($limit, $offset, $request->bearerToken());

            if (is_null($response)) {
                return ApiResponseHandler::error('No se encontraron vehículos.', 404);
            }

            return ApiResponseHandler::paginated($response['data'], 200, $response['pagination']);
        } catch (Throwable $e) {
            return ApiResponseHandler::error('Error interno del servidor.', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * @group API | GET Vehicles
     * Obtener los detalles de un vehículo específico
     *
     * Este endpoint devuelve la información detallada de un vehículo por su ID.
     *
     * @urlParam id int required El ID del vehículo. Example: 1
     * @header Accept application/json
     * @header Authorization Bearer <TOKEN>
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Operación exitosa",
     *   "data": {
     *       "id": 1,
     *       "name": "Speeder Bike",
     *       "model": "74-Z speeder bike",
     *       "manufacturer": "Aratech Repulsorlift Company",
     *       "cost_in_credits": "5000",
     *       "length": "3.4",
     *       "max_atmosphering_speed": "200",
     *       "crew": "1",
     *       "passengers": "0",
     *       "cargo_capacity": "4",
     *       "consumables": "None",
     *       "vehicle_class": "speeder bike"
     *   }
     * }
     * @response 404 {
     *   "status": "error",
     *   "message": "Vehículo no encontrado.",
     *   "errors": []
     * }
     * @response 500 {
     *   "status": "error",
     *   "message": "Error interno del servidor.",
     *   "error": "Detalles del error"
     * }
     */
    public function show($id, Request $request): JsonResponse
    {
        try {
            // Llama al servicio para obtener los detalles de un vehículo por su ID.
            $response = $this->vehicleService->getVehicleById($id, $request->bearerToken());

            // Si la respuesta indica un error, devuelve un mensaje de error con código 404.
            if (is_null($response)) {
                return ApiResponseHandler::error('Vehículo no encontrado.', 404);
            }

            // Devuelve los detalles del vehículo en formato JSON.
            return ApiResponseHandler::success($response);
        } catch (Throwable $e) {
            return ApiResponseHandler::error('Error interno del servidor.', 500, ['exception' => $e->getMessage()]);
        }
    }

}
