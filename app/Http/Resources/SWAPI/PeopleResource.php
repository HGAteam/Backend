<?php

namespace App\Http\Resources\SWAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeopleResource extends JsonResource
{
    /**
     * Transformar el recurso en un array JSON.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource['name'] ?? '',
            'height' => $this->resource['height'] ?? '',
            'mass' => $this->resource['mass'] ?? '',
            'hair_color' => $this->resource['hair_color'] ?? '',
            'skin_color' => $this->resource['skin_color'] ?? '',
            'eye_color' => $this->resource['eye_color'] ?? '',
            'birth_year' => $this->resource['birth_year'] ?? '',
            'gender' => $this->resource['gender'] ?? '',
            'homeworld' => isset($this->resource['homeworld']) ? url('/api/planets/' . $this->getIdFromUrl($this->resource['homeworld'])) : '',
            'films' => array_map(fn($film) => url('/api/films/' . $this->getIdFromUrl($film)), $this->resource['films'] ?? []),
            'species' => array_map(fn($species) => url('/api/species/' . $this->getIdFromUrl($species)), $this->resource['species'] ?? []),
            'vehicles' => array_map(fn($vehicle) => url('/api/vehicles/' . $this->getIdFromUrl($vehicle)), $this->resource['vehicles'] ?? []),
            'starships' => array_map(fn($starship) => url('/api/starships/' . $this->getIdFromUrl($starship)), $this->resource['starships'] ?? []),
            'created' => $this->resource['created'] ?? '',
            'edited' => $this->resource['edited'] ?? '',
            'url' => url('/api/people/' . $this->getIdFromUrl($this->resource['url'] ?? '')),
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
