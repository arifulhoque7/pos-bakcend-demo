<?php

namespace App\Services\V1;

use App\Models\PurchaseItem;

class PurchaseItemService
{
    /**
     * Get all purchase items
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findAll()
    {
        return PurchaseItem::with(['purchase.supplier', 'product'])->paginate();
    }

    /**
     * Get a purchase item by ID
     *
     * @param int $id
     * @return PurchaseItem
     */
    public function findOne(int $id)
    {
        return PurchaseItem::with(['purchase.supplier', 'product'])->findOrFail($id);
    }

    /**
     * Create a new purchase item
     *
     * @param array $data
     * @return PurchaseItem
     */
    public function create(array $data)
    {
        $data['total_price'] = $data['quantity'] * $data['unit_price'];
        return PurchaseItem::create($data);
    }

    /**
     * Update a purchase item
     *
     * @param int $id
     * @param array $data
     * @return PurchaseItem
     */
    public function update(int $id, array $data)
    {
        $purchaseItem = $this->findOne($id);

        // Calculate total price if quantity or unit price changes
        if (isset($data['quantity']) || isset($data['unit_price'])) {
            $data['total_price'] = ($data['quantity'] ?? $purchaseItem->quantity) * 
                                   ($data['unit_price'] ?? $purchaseItem->unit_price);
        }

        $purchaseItem->update($data);

        return $purchaseItem;
    }

    /**
     * Delete a purchase item
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return PurchaseItem::destroy($id) > 0;
    }
}
