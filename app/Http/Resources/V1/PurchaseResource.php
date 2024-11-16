<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type'       => 'purchase',
            'id'         => $this->id,
            'attributes' => [
                'supplier_id'   => $this->supplier_id,
                'supplier_name' => $this->whenLoaded('supplier', fn() => $this->supplier->name),
                'total_amount'  => $this->total_amount,
                'purchase_date' => $this->purchase_date,
                'items' => PurchaseItemResource::collection($this->whenLoaded('purchaseItems')),
                'createdAt'    => $this->when($request->routeIs('purchases.*'), $this->created_at),
                'updatedAt'    => $this->when($request->routeIs('purchases.*'), $this->updated_at),
            ],
            'links' => [
                'self' => route('purchases.show', ['purchase' => $this->id]),
            ],
        ];
    }
}
