<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type'       => 'user',
            'id'         => $this->id,
            'attributes' => [
                'name'      => $this->name,
                'email'     => $this->email,
                'createdAt' => $this->when($request->routeIs('users.*'), $this->created_at),
                'updatedAt' => $this->when($request->routeIs('users.*'), $this->updated_at),
            ],
            'links' => [
                'self' => route('users.show', ['user' => $this->id]),
            ],

        ];
    }
}
