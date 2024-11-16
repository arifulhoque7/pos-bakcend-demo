<?php

namespace App\Services\V1;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    protected $purchaseItemService;
    protected $productService;

    public function __construct(PurchaseItemService $purchaseItemService, ProductService $productService)
    {
        $this->purchaseItemService = $purchaseItemService;
        $this->productService = $productService;
    }

    /**
     * Get all purchases
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findAll()
    {
        return Purchase::with(['supplier', 'purchaseItems'])->paginate();
    }

    /**
     * Get a purchase by ID
     *
     * @param int $id
     * @return Purchase
     */
    public function findOne(int $id)
    {
        return Purchase::with(['supplier', 'purchaseItems'])->findOrFail($id);
    }

    /**
     * Create a new purchase along with its items
     *
     * @param array $data
     * @return Purchase
     */
    public function create(array $data)
    {
        // Start transaction to ensure atomicity
        DB::beginTransaction();

        try {
            if (!isset($data['items']) || empty($data['items'])) {
                throw new \Exception('Purchase items are required');
            }

            // Create the purchase
            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'total_amount' => $data['total_amount'],
                'purchase_date' => $data['purchase_date']
            ]);

            // Create the purchase items

            foreach ($data['items'] as $item) {
                $item['purchase_id'] = $purchase->id;
                $this->purchaseItemService->create($item);
                $this->productService->updateStock($item['product_id'], $item['quantity']);
            }

            // Commit the transaction
            DB::commit();

            return $purchase;
        } catch (\Exception $e) {
            // Rollback if there is any error
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing purchase and its items
     *
     * @param int $id
     * @param array $data
     * @return Purchase
     */
    public function update(int $id, array $data)
    {
        // Start transaction to ensure atomicity
        // dd($data);
        DB::beginTransaction();

        try {
            if (!isset($data['items']) || empty($data['items'])) {
                throw new \Exception('Purchase items are required');
            }

            // Update the purchase
            $purchase = $this->findOne($id);
            $purchase->update([
                'supplier_id' => $data['supplier_id'] ?? $purchase->supplier_id,
                'total_amount' => $data['total_amount'] ?? $purchase->total_amount,
                'purchase_date' => $data['purchase_date'] ?? $purchase->purchase_date
            ]);

            // Update purchase items
            // Delete all existing items
            //before deleting the items, we need to reduce the stock of the products
            foreach ($purchase->purchaseItems as $item) {
                $this->productService->reduceStock($item->product_id, $item->quantity);
            }

            $purchase->purchaseItems()->delete();

            // Add new items
            foreach ($data['items'] as $item) {
                $item['purchase_id'] = $purchase->id;
                $this->purchaseItemService->create($item);
                $this->productService->updateStock($item['product_id'], $item['quantity']);
            }

            // Commit the transaction
            DB::commit();

            $purchase->refresh();
            return $purchase;
        } catch (\Exception $e) {
            // Rollback if there is any error
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a purchase and its associated items
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        $purchase = $this->findOne($id);

        if (!$purchase) {
            throw new \Exception("Purchase not found");
        }
        if ($purchase->purchaseItems()->exists()) {
            foreach ($purchase->purchaseItems as $item) {
                $this->productService->reduceStock($item->product_id, $item->quantity);
            }

            $purchase->purchaseItems()->delete();
        }

        return $purchase->delete();
    }
}
