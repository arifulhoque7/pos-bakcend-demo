<?php

namespace App\Services\V1;

use App\Models\Category;

class CategoryService
{
    /**
     * Get all categories
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findAll()
    {
        return Category::paginate();
    }

    /**
     * Get a category by id
     *
     * @param int $id
     * @return Category
     */
    public function findOne(int $id)
    {
        return Category::findOrFail($id);
    }

    /**
     * Create a new category
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data)
    {
        return Category::create($data);
    }

    /**
     * Update a category
     *
     * @param int $id
     * @param array $data
     * @return Category
     */
    public function update(int $id, array $data)
    {
        $category = $this->findOne($id);
        $category->update($data);
        return $category;
    }

    /**
     * Delete a category
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return Category::destroy($id) > 0;
    }
}
