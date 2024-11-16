<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type'       => 'purchase_item',
            'id'         => $this->id,
            'attributes' => [
                'purchase_id'  => $this->purchase_id,
                'product_id'   => $this->product_id,
                'product_name' => $this->whenLoaded('product', fn() => $this->product->name),
                'quantity'    => $this->quantity,
                'unit_price'   => $this->unit_price,
                'total_price'  => $this->total_price,
                'createdAt'   => $this->when($request->routeIs('purchase-items.*'), $this->created_at),
                'updatedAt'   => $this->when($request->routeIs('purchase-items.*'), $this->updated_at),
            ]
        ];
    }
}
