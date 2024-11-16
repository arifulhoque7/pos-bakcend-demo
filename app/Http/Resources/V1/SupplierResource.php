<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type'       => 'supplier',
            'id'         => $this->id,
            'attributes' => [
                'name'        => $this->name,
                'contact_info' => $this->contact_info,
                'address'     => $this->address,
                'createdAt'   => $this->when($request->routeIs('suppliers.*'), $this->created_at),
                'updatedAt'   => $this->when($request->routeIs('suppliers.*'), $this->updated_at),
            ],
            'links' => [
                'self' => route('suppliers.show', ['supplier' => $this->id]),
            ],
        ];
    }
}
