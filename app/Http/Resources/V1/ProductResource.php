<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type'       => 'product',
            'id'         => $this->id,
            'attributes' => [
                'name'                  => $this->name,
                'SKU'                   => $this->SKU,
                'price'                 => $this->price,
                'initial_stock_quantity'  => $this->initial_stock_quantity,
                'current_stock_quantity'  => $this->current_stock_quantity,
                'category_id'            => $this->category_id,
                'category_name'          => $this->whenLoaded('category', fn() => $this->category->name),
                'createdAt'             => $this->when($request->routeIs('products.*'), $this->created_at),
                'updatedAt'             => $this->when($request->routeIs('products.*'), $this->updated_at),
            ],
            'links' => [
                'self' => route('products.show', ['product' => $this->id]),
            ],
        ];
    }
}
