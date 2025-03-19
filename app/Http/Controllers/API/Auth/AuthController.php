<?php
namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Services\Api\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    /**
     * @group API Autenticación
     * Registrar un nuevo usuario
     *
     * Este endpoint permite registrar un nuevo usuario en la API. No se necesita autenticación para esta solicitud.
     *
     * @bodyParam name string required El nombre del usuario. Example: Juan Pérez
     * @bodyParam email string required El correo electrónico del usuario. Example: usuario@ejemplo.com
     * @bodyParam password string required La contraseña del usuario. Example: secret
     * @bodyParam password_confirmation string required Confirmación de la contraseña. Example: secret
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Usuario registrado exitosamente.",
     *   "user": {
     *     "id": 1,
     *     "name": "Juan Pérez",
     *     "email": "usuario@ejemplo.com"
     *   }
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Error en la validación.",
     *   "errors": {
     *     "email": ["El campo email es obligatorio."]
     *   }
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->register($request->validated());

            return response()->json($data, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error en la validación.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No se pudo registrar el usuario.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @group API Autenticación
     * Iniciar sesión y obtener un token de acceso
     *
     * Este endpoint permite iniciar sesión con un usuario registrado.  
     * Para acceder a los recursos protegidos de la API, debes usar el token generado en el encabezado `Authorization`.  
     * 
     * **IMPORTANTE:**  
     * - Si no tienes una cuenta, puedes registrarte utilizando el endpoint de registro.  
     * - También puedes solicitar un usuario de prueba.  
     * - Una vez autenticado, el token obtenido debe ser enviado en todas las solicitudes protegidas.
     *
     * @bodyParam email string required El correo electrónico del usuario. Example: usuario@ejemplo.com
     * @bodyParam password string required La contraseña del usuario. Example: secret
     * 
     * @response 200 {
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *   "token_type": "Bearer",
     *   "expires_in": 3600
     * }
     * @response 401 {
     *   "status": "error",
     *   "message": "Credenciales inválidas."
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->login($request->validated());

            if (isset($data['code']) && $data['code'] === '401') {
                return response()->json($data, 401);
            }

            return response()->json($data, 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No se pudo iniciar sesión.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

        /**
     * @group API Autenticación
     * Verificar si el usuario está autenticado
     *
     * Este endpoint permite verificar si el usuario está autenticado.  
     * Se debe enviar un **token válido** en el encabezado `Authorization`.
     *
     * @header Authorization Bearer <TOKEN>
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Usuario autenticado.",
     *   "user": {
     *     "id": 1,
     *     "name": "Juan Pérez",
     *     "email": "usuario@ejemplo.com"
     *   }
     * }
     * @response 401 {
     *   "status": "error",
     *   "message": "Usuario no autenticado."
     * }
     */
    public function isAuth(Request $request): JsonResponse
    {
        $data = $this->authService->isAuthenticated($request->user());

        return response()->json([
            'status'  => $data['status'],
            'message' => $data['message'],
            'user'    => $data['user'] ?? null,
        ], $data['status'] === 'success' ? 200 : 401);
    }

/**
     * @group API Autenticación
     * Cerrar sesión del usuario autenticado
     *
     * Este endpoint permite cerrar la sesión de un usuario autenticado.  
     * Se debe enviar un **token válido** en el encabezado `Authorization`.
     *
     * @header Authorization Bearer <TOKEN>
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Sesión cerrada exitosamente."
     * }
     * @response 401 {
     *   "status": "error",
     *   "message": "No se pudo cerrar la sesión."
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        $data = $this->authService->logout($request->user());

        return response()->json([
            'status'  => $data['status'],
            'message' => $data['message'],
        ], $data['status'] === 'success' ? 200 : 401);
    }
}
