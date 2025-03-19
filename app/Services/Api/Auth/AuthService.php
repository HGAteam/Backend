<?php
namespace App\Services\Api\Auth;

use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Registra un nuevo usuario en el sistema.
     *
     * @param array $data Datos del usuario a registrar (nombre, correo, contraseña).
     * @return array Respuesta con el estado del registro y los datos del usuario.
     */
    public function register(array $data)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            DB::commit();

            return [
                'status'  => 'success',
                'message' => 'Usuario registrado correctamente.',
                'user'    => new UserResource($user),
                'note'    => 'Inicia sesión para obtener tu token de acceso.',
            ];

        } catch (Exception $e) {
            DB::rollBack();
            return [
                'status'  => 'error',
                'message' => 'No se pudo registrar el usuario.',
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Inicia sesión con las credenciales proporcionadas.
     *
     * @param array $credentials Credenciales del usuario (correo y contraseña).
     * @return array Respuesta con el estado del inicio de sesión, datos del usuario y token.
     */
    public function login(array $credentials)
    {
        if (! Auth::guard('web')->attempt($credentials)) {
            return [
                'code'    => '401',
                'status'  => 'error',
                'message' => 'Credenciales incorrectas.',
            ];
        }

        $user = Auth::user();

        // Eliminar solo el token actual si ya existe
        $user->tokens()->delete();

        // Crear nuevo token
        $token = $user->createToken('API Token')->accessToken;

        return [
            'status'  => 'success',
            'message' => 'Inicio de sesión exitoso.',
            'user'    => new UserResource($user),
            'token'   => $token,
            'note'    => 'Guarda este token para futuras solicitudes en el header: Authorization: Bearer {token}',
        ];
    }

    /**
     * Verifica si el usuario está autenticado.
     *
     * @param mixed $user Usuario autenticado o null si no está autenticado.
     * @return array Respuesta con el estado de autenticación y datos del usuario.
     */
    public function isAuthenticated($user)
    {
        if (! $user) {
            return [
                'status'  => 'error',
                'message' => 'No autenticado.',
            ];
        }

        return [
            'status'  => 'success',
            'message' => 'Usuario autenticado.',
            'user'    => new UserResource($user),
        ];
    }

    /**
     * Cierra la sesión del usuario autenticado eliminando su token.
     *
     * @param mixed $user Usuario autenticado o null si no está autenticado.
     * @return array Respuesta con el estado del cierre de sesión.
     */
    public function logout($user)
    {
        if (!$user) {
            return [
                'status'  => 'error',
                'message' => 'Usuario no autenticado.',
            ];
        }
    
        // Obtener el token de Passport (si existe)
        $token = $user->tokens()->latest()->first(); 
    
        if ($token) {
            $token->revoke();
            $token->delete();
        }
    
        return [
            'status'  => 'success',
            'message' => 'Sesión cerrada correctamente.',
        ];
    }
    
}
