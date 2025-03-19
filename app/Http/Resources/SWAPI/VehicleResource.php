<?php

namespace App\Http\Resources\SWAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="VehicleResource",
 *     type="object",
 *     @OA\Property(property="name", type="string", description="Nombre del vehículo"),
 *     @OA\Property(property="model", type="string", description="Modelo del vehículo"),
 *     @OA\Property(property="manufacturer", type="string", description="Fabricante del vehículo"),
 *     @OA\Property(property="cost_in_credits", type="string", description="Costo en créditos"),
 *     @OA\Property(property="length", type="string", description="Longitud del vehículo"),
 *     @OA\Property(property="max_atmosphering_speed", type="string", description="Velocidad máxima"),
 *     @OA\Property(property="crew", type="string", description="Cantidad de tripulación"),
 *     @OA\Property(property="passengers", type="string", description="Cantidad de pasajeros"),
 *     @OA\Property(property="cargo_capacity", type="string", description="Capacidad de carga"),
 *     @OA\Property(property="consumables", type="string", description="Duración de consumibles"),
 *     @OA\Property(property="vehicle_class", type="string", description="Clase del vehículo"),
 *     @OA\Property(property="pilots", type="array", @OA\Items(type="string"), description="URLs de los pilotos"),
 *     @OA\Property(property="films", type="array", @OA\Items(type="string"), description="URLs de las películas"),
 *     @OA\Property(property="created", type="string", format="date-time", description="Fecha de creación"),
 *     @OA\Property(property="edited", type="string", format="date-time", description="Fecha de edición"),
 *     @OA\Property(property="url", type="string", description="URL del recurso")
 * )
 */
class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
			'name' => $this->resource['name'] ?? '',
			'model' => $this->resource['model'] ?? '',
			'manufacturer' => $this->resource['manufacturer'] ?? '',
			'cost_in_credits' => $this->resource['cost_in_credits'] ?? '',
			'length' => $this->resource['length'] ?? '',
			'max_atmosphering_speed' => $this->resource['max_atmosphering_speed'] ?? '',
			'crew' => $this->resource['crew'] ?? '',
			'passengers' => $this->resource['passengers'] ?? '',
			'cargo_capacity' => $this->resource['cargo_capacity'] ?? '',
			'consumables' => $this->resource['consumables'] ?? '',
			'vehicle_class' => $this->resource['vehicle_class'] ?? '',
            'pilots' => array_map(fn($pilots) => url('/api/people/' . $this->getIdFromUrl($pilots)), $this->resource['pilots'] ?? []),
			'films' => $this->resource['films'] ?? [],
            'created' => $this->resource['created'] ?? '',
            'edited' => $this->resource['edited'] ?? '',
            'url' => url('/api/vehicles/' . $this->getIdFromUrl($this->resource['url'] ?? '')),
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
