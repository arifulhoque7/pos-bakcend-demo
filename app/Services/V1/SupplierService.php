<?php

namespace App\Services\V1;

use App\Models\Supplier;

class SupplierService
{
    /**
     * Get all suppliers
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findAll()
    {
        return Supplier::paginate();
    }

    /**
     * Get a supplier by id
     *
     * @param int $id
     * @return Supplier
     */
    public function findOne(int $id)
    {
        return Supplier::findOrFail($id);
    }

    /**
     * Create a new supplier
     *
     * @param array $data
     * @return Supplier
     */
    public function create(array $data)
    {
        return Supplier::create($data);
    }

    /**
     * Update a supplier
     *
     * @param int $id
     * @param array $data
     * @return Supplier
     */
    public function update(int $id, array $data)
    {
        $supplier = $this->findOne($id);
        $supplier->update($data);
        return $supplier;
    }

    /**
     * Delete a supplier
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return Supplier::destroy($id) > 0;
    }
}
