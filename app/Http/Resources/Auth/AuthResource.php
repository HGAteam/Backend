<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AuthResource",
 *     type="object",
 *     @OA\Property(property="status", type="string", description="Estado de la autenticación"),
 *     @OA\Property(property="message", type="string", description="Mensaje de la respuesta"),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", description="ID del usuario"),
 *         @OA\Property(property="name", type="string", description="Nombre del usuario"),
 *         @OA\Property(property="email", type="string", format="email", description="Correo electrónico del usuario")
 *     ),
 *     @OA\Property(property="token", type="string", description="Token de autenticación")
 * )
 */
class AuthResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'status'  => 'success',
            'message' => $this->message,
            'user'    => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ],
            'token'   => $this->token,
        ];
    }
}
