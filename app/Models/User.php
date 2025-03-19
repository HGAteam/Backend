<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos en serialización.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de atributos a tipos específicos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define el guard para Spatie Laravel Permission.
     */
    protected $guard_name = 'api';

    /**
     * Asigna un rol al usuario si no lo tiene.
     */
    public function assignRoleToUser(string $role): void
    {
        if (!$this->hasRole($role)) {
            $this->assignRole($role);
        }
    }

    /**
     * Verifica si el usuario tiene uno o más roles.
     */
    public function hasAnyOfRoles(...$roles): bool
    {
        return $this->hasAnyRole(Arr::flatten($roles)); // Solución correcta
    }

    /**
     * Revoca un rol específico del usuario.
     */
    public function removeUserRole(string $role): void
    {
        if ($this->hasRole($role)) {
            $this->removeRole($role);
        }
    }

    /**
     * Verifica si el usuario tiene un permiso específico.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->hasPermissionTo($permission);
    }

    /**
     * Otorga un permiso al usuario si no lo tiene.
     */
    public function grantPermissionTo(string $permission): void
    {
        if (!$this->hasPermissionTo($permission)) {
            $this->givePermissionTo($permission);
        }
    }

    /**
     * Revoca un permiso específico del usuario.
     */
    public function revokePermission(string $permission): void
    {
        if ($this->hasPermissionTo($permission)) {
            $this->revokePermissionTo($permission);
        }
    }

    /**
     * Obtiene todos los roles asignados al usuario.
     */
    public function getUserRoles(): array
    {
        return $this->roles->pluck('name')->toArray();
    }

    /**
     * Obtiene todos los permisos asignados al usuario.
     */
    public function getUserPermissions(): array
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }
}
