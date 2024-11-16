<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\V1\CategoryService;
use App\Http\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\V1\CreateCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;

/**
 * Class CategoryController
 * Handles HTTP requests related to category operations and delegates business logic to the CategoryService.
 */
class CategoryController extends Controller
{
    use ApiResponses;

    /**
     * @var CategoryService The service handling category data processes
     */
    protected $categoryService;

    /**
     * CategoryController constructor.
     * Injects CategoryService to manage category-related business logic.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of all categories.
     * Retrieves all categories from the database using CategoryService and returns them as a resource collection.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $categories = $this->categoryService->findAll();
        return CategoryResource::collection($categories);
    }

    /**
     * Display the specified category.
     * Retrieves a single category by ID using CategoryService and returns it as a category resource.
     *
     * @param int $id Category ID
     * @return CategoryResource
     */
    public function show(int $id)
    {
        try {
            $category = $this->categoryService->findOne($id);
            return $this->success("Category found", new CategoryResource($category), 200);
        } catch (\Exception $e) {
            return $this->error("Category not found", 404);
        }
    }

    /**
     * Store a newly created category in storage.
     * Validates the request, creates a category using CategoryService, and returns the category as a resource.
     *
     * @param CreateCategoryRequest $request
     * @return CategoryResource
     */
    public function store(CreateCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $category = $this->categoryService->create($data);
            return $this->success("Category created successfully", new CategoryResource($category), 201);
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    /**
     * Update the specified category in storage.
     * Validates the request, updates the category using CategoryService, and returns the updated category as a resource.
     *
     * @param UpdateCategoryRequest $request
     * @param int $id Category ID
     * @return CategoryResource
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        try {
            $data = $request->validated();
            $category = $this->categoryService->update($id, $data);
            return $this->success("Category updated successfully", new CategoryResource($category), 200);
        } catch (\Exception $e) {
            return $this->error("Category not found", 404);
        }
    }

    /**
     * Remove the specified category from storage.
     * Deletes a category by ID using CategoryService and returns a success response if successful.
     *
     * @param int $id Category ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($this->categoryService->delete($id)) {
            return $this->ok("Category deleted successfully");
        }
        return $this->error("Category not found", 404);
    }
}
