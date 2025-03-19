<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Api\PeopleService;
use App\Utils\ApiResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PeopleController extends Controller
{
    protected $peopleService;

    /**
     * Constructor para inicializar el servicio de personas.
     *
     * @param PeopleService $peopleService
     */
    public function __construct(PeopleService $peopleService)
    {
        $this->peopleService = $peopleService;
    }

    /**
     * @group API | GET People
     * Obtener una lista de personas
     *
     * Este endpoint devuelve una lista paginada de personajes de Star Wars.
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
     *       "name": "Luke Skywalker",
     *       "height": "172",
     *       "mass": "77",
     *       "hair_color": "blond",
     *       "skin_color": "fair",
     *       "eye_color": "blue",
     *       "birth_year": "19BBY",
     *       "gender": "male"
     *     }
     *   ],
     *   "pagination": {
     *     "total": 82,
     *     "limit": 10,
     *     "offset": 0,
     *     "next": "/api/people?limit=10&offset=10",
     *     "previous": null
     *   }
     * }
     * @response 404 {
     *   "status": "error",
     *   "message": "No se encontraron personas.",
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

            // Llama al servicio para obtener la lista de personas.
            $response = $this->peopleService->getPeople($limit, $offset, $request->bearerToken());

            if (is_null($response)) {
                return ApiResponseHandler::error('No se encontraron personas.', 404);
            }

            return ApiResponseHandler::paginated($response['data'], 200, $response['pagination']);
        } catch (Throwable $e) {
            return ApiResponseHandler::error('Error interno del servidor.', 500, ['exception' => $e->getMessage()]);
        }
    }

     /**
     * @group API | GET People
     * Obtener los detalles de una persona específica
     *
     * Este endpoint devuelve la información detallada de una persona por su ID.
     *
     * @urlParam id int required El ID de la persona. Example: 1
     * @header Accept application/json
     * @header Authorization Bearer <TOKEN>
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Operación exitosa",
     *   "data": {
     *       "id": 1,
     *       "name": "Luke Skywalker",
     *       "height": "172",
     *       "mass": "77",
     *       "hair_color": "blond",
     *       "skin_color": "fair",
     *       "eye_color": "blue",
     *       "birth_year": "19BBY",
     *       "gender": "male"
     *   }
     * }
     * @response 404 {
     *   "status": "error",
     *   "message": "Persona no encontrada.",
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
            // Llama al servicio para obtener los detalles de una persona por su ID.
            $response = $this->peopleService->getPersonById($id);

            // Si la respuesta indica un error, devuelve un mensaje de error con código 404.
            if (is_null($response)) {
                return ApiResponseHandler::error('Persona no encontrada.', 404);
            }

            // Devuelve los detalles de la persona en formato JSON.
            return ApiResponseHandler::success($response);
        } catch (Throwable $e) {
            return ApiResponseHandler::error('Error interno del servidor.', 500, ['exception' => $e->getMessage()]);
        }
    }
}
