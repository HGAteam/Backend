<?php

namespace App\Http\Resources\SWAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PlanetResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", description="ID del planeta"),
 *     @OA\Property(property="name", type="string", description="Nombre del planeta"),
 *     @OA\Property(property="rotation_period", type="string", description="Periodo de rotación"),
 *     @OA\Property(property="orbital_period", type="string", description="Periodo orbital"),
 *     @OA\Property(property="diameter", type="string", description="Diámetro del planeta"),
 *     @OA\Property(property="climate", type="string", description="Clima del planeta"),
 *     @OA\Property(property="gravity", type="string", description="Gravedad del planeta"),
 *     @OA\Property(property="terrain", type="string", description="Terreno del planeta"),
 *     @OA\Property(property="surface_water", type="string", description="Porcentaje de agua superficial"),
 *     @OA\Property(property="population", type="string", description="Población del planeta"),
 *     @OA\Property(property="residents", type="array", @OA\Items(type="string"), description="URLs de los residentes"),
 *     @OA\Property(property="films", type="array", @OA\Items(type="string"), description="URLs de las películas"),
 *     @OA\Property(property="created", type="string", format="date-time", description="Fecha de creación"),
 *     @OA\Property(property="edited", type="string", format="date-time", description="Fecha de edición"),
 *     @OA\Property(property="url", type="string", description="URL del recurso")
 * )
 */
class PlanetResource extends JsonResource
{
    /**
     * Transformar el recurso en un array JSON.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getIdFromUrl($this->resource['url'] ?? ''),
            'name' => $this->resource['name'] ?? '',
            'rotation_period' => $this->resource['rotation_period'] ?? '',
            'orbital_period' => $this->resource['orbital_period'] ?? '',
            'diameter' => $this->resource['diameter'] ?? '',
            'climate' => $this->resource['climate'] ?? '',
            'gravity' => $this->resource['gravity'] ?? '',
            'terrain' => $this->resource['terrain'] ?? '',
            'surface_water' => $this->resource['surface_water'] ?? '',
            'population' => $this->resource['population'] ?? '',
            'residents' => array_map(fn($resident) => url('/api/people/' . $this->getIdFromUrl($resident)), $this->resource['residents'] ?? []),
            'films' => $this->resource['films'] ?? [],
            'created' => $this->resource['created'] ?? '',
            'edited' => $this->resource['edited'] ?? '',
            'url' => url('/api/planets/' . $this->getIdFromUrl($this->resource['url'] ?? '')),
        ];
    }

    /**
     * Extraer ID numérico desde la URL de SWAPI.
     */
    private function getIdFromUrl(string $url): ?int
    {
        if (preg_match('/\/(\d+)\/$/', $url, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }
}
