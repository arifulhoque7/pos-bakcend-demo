<?php

namespace App\Services\V1;

use App\Models\Product;

class ProductService
{
    /**
     * Get all products
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findAll()
    {
        return Product::with('category')->paginate();
    }

    /**
     * Get a product by id
     *
     * @param int $id
     * @return Product
     */
    public function findOne(int $id)
    {
        return Product::with('category')->findOrFail($id);
    }

    /**
     * Create a new product
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data)
    {
        $data['current_stock_quantity'] = $data['initial_stock_quantity'];
        return Product::create($data);
    }

    /**
     * Update a product
     *
     * @param int $id
     * @param array $data
     * @return Product
     */
    public function update(int $id, array $data)
    {
        $product = $this->findOne($id);
        $product->update($data);
        return $product;
    }

    /**
     * Delete a product
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return Product::destroy($id) > 0;
    }

    /**
     * Update stock quantities for a product
     *
     * @param int $id
     * @param int $quantity
     * @return Product
     */
    public function updateStock(int $id, int $quantity)
    {
        $product = $this->findOne($id);
        $product->current_stock_quantity += $quantity;
        $product->save();
        return $product;
    }

    /**
     * Reduce stock quantities for a product
     *
     * @param int $id
     * @param int $quantity
     * @return Product
     * @throws \Exception
     */
    public function reduceStock(int $id, int $quantity)
    {
        $product = $this->findOne($id);

        if ($product->current_stock_quantity < $quantity) {
            throw new \Exception("Insufficient stock for product ID {$id}");
        }

        $product->current_stock_quantity -= $quantity;
        $product->save();
        return $product;
    }
}
