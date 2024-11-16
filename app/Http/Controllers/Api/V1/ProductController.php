<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\V1\ProductService;
use App\Http\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\V1\CreateProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;

/**
 * Class ProductController
 * Handles HTTP requests related to product operations and delegates business logic to the ProductService.
 */
class ProductController extends Controller
{
    use ApiResponses;

    /**
     * @var ProductService The service handling product data processes
     */
    protected $productService;

    /**
     * ProductController constructor.
     * Injects ProductService to manage product-related business logic.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of all products.
     * Retrieves all products from the database using ProductService and returns them as a resource collection.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $products = $this->productService->findAll();
        return ProductResource::collection($products);
    }

    /**
     * Display the specified product.
     * Retrieves a single product by ID using ProductService and returns it as a product resource.
     *
     * @param int $id Product ID
     * @return ProductResource
     */
    public function show(int $id)
    {
        try {
            $product = $this->productService->findOne($id);
            return $this->success("Product found", new ProductResource($product), 200);
        } catch (\Exception $e) {
            return $this->error("Product not found", 404);
        }
    }

    /**
     * Store a newly created product in storage.
     * Validates the request, creates a product using ProductService, and returns the product as a resource.
     *
     * @param CreateProductRequest $request
     * @return ProductResource
     */
    public function store(CreateProductRequest $request)
    {
        try {
            $data = $request->validated();
            $product = $this->productService->create($data);
            return $this->success("Product created successfully", new ProductResource($product), 201);
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    /**
     * Update the specified product in storage.
     * Validates the request, updates the product using ProductService, and returns the updated product as a resource.
     *
     * @param UpdateProductRequest $request
     * @param int $id Product ID
     * @return ProductResource
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        try {
            $data = $request->validated();
            $product = $this->productService->update($id, $data);
            return $this->success("Product updated successfully", new ProductResource($product), 200);
        } catch (\Exception $e) {
            return $this->error("Product not found", 404);
        }
    }

    /**
     * Remove the specified product from storage.
     * Deletes a product by ID using ProductService and returns a success response if successful.
     *
     * @param int $id Product ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($this->productService->delete($id)) {
            return $this->ok("Product deleted successfully");
        }
        return $this->error("Product not found", 404);
    }
}
