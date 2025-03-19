<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ApiResponseHandler
{
    /**
     * Respuesta de éxito estandarizada con soporte para encabezados personalizados.
     *
     * @param array|JsonResource|Collection|string|null $data
     * @param int $statusCode
     * @param string|null $message
     * @param array $headers
     * @return JsonResponse
     */
    public static function success($data = null, int $statusCode = 200, string $message = null, array $headers = []): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message ?? 'Operación exitosa',
            'data'    => self::formatData($data),
        ], $statusCode, $headers);
    }

    /**
     * Respuesta de error estandarizada con soporte para encabezados personalizados.
     *
     * @param string $message
     * @param int $statusCode
     * @param array|null $errors
     * @param array $headers
     * @return JsonResponse
     */
    public static function error(string $message, int $statusCode = 400, ?array $errors = null, array $headers = []): JsonResponse
    {
        $response = [
            'status'  => 'error',
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode, $headers);
    }

    /**
     * Respuesta de paginación estandarizada.
     *
     * @param array|JsonResource|Collection $data
     * @param int $statusCode
     * @param array $pagination
     * @param string|null $message
     * @param array $headers
     * @return JsonResponse
     */
    public static function paginated($data, int $statusCode = 200, array $pagination = [], string $message = null, array $headers = []): JsonResponse
    {
        return response()->json([
            'status'     => 'success',
            'message'    => $message ?? 'Operación exitosa',
            'data'       => self::formatData($data),
            'pagination' => $pagination
        ], $statusCode, $headers);
    }

    /**
     * Formatea los datos para asegurar que sean serializables.
     *
     * @param mixed $data
     * @return mixed
     */
    private static function formatData($data)
    {
        if ($data instanceof JsonResource || $data instanceof Collection) {
            return $data->toArray(request());
        }

        return $data;
    }
}
