<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type'       => 'category',
            'id'         => $this->id,
            'attributes' => [
                'name'        => $this->name,
                'description' => $this->description,
                'createdAt'   => $this->when($request->routeIs('categories.*'), $this->created_at),
                'updatedAt'   => $this->when($request->routeIs('categories.*'), $this->updated_at),
            ],
            'links' => [
                'self' => route('categories.show', ['category' => $this->id]),
            ],
        ];
    }
}
