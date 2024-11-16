<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\V1\SupplierService;
use App\Http\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SupplierResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\V1\CreateSupplierRequest;
use App\Http\Requests\Api\V1\UpdateSupplierRequest;

/**
 * Class SupplierController
 * Handles HTTP requests related to supplier operations and delegates business logic to the SupplierService.
 */
class SupplierController extends Controller
{
    use ApiResponses;

    /**
     * @var SupplierService The service handling supplier data processes
     */
    protected $supplierService;

    /**
     * SupplierController constructor.
     * Injects SupplierService to manage supplier-related business logic.
     *
     * @param SupplierService $supplierService
     */
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * Display a listing of all suppliers.
     * Retrieves all suppliers from the database using SupplierService and returns them as a resource collection.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $suppliers = $this->supplierService->findAll();
        return SupplierResource::collection($suppliers);
    }

    /**
     * Display the specified supplier.
     * Retrieves a single supplier by ID using SupplierService and returns it as a supplier resource.
     *
     * @param int $id Supplier ID
     * @return SupplierResource
     */
    public function show(int $id)
    {
        try {
            $supplier = $this->supplierService->findOne($id);
            return $this->success("Supplier found", new SupplierResource($supplier), 200);
        } catch (\Exception $e) {
            return $this->error("Supplier not found", 404);
        }
    }

    /**
     * Store a newly created supplier in storage.
     * Validates the request, creates a supplier using SupplierService, and returns the supplier as a resource.
     *
     * @param CreateSupplierRequest $request
     * @return SupplierResource
     */
    public function store(CreateSupplierRequest $request)
    {
        try {
            $data = $request->validated();
            $supplier = $this->supplierService->create($data);
            return $this->success("Supplier created successfully", new SupplierResource($supplier), 201);
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    /**
     * Update the specified supplier in storage.
     * Validates the request, updates the supplier using SupplierService, and returns the updated supplier as a resource.
     *
     * @param UpdateSupplierRequest $request
     * @param int $id Supplier ID
     * @return SupplierResource
     */
    public function update(UpdateSupplierRequest $request, int $id)
    {
        try {
            $data = $request->validated();
            $supplier = $this->supplierService->update($id, $data);
            return $this->success("Supplier updated successfully", new SupplierResource($supplier), 200);
        } catch (\Exception $e) {
            return $this->error("Supplier not found", 404);
        }
    }

    /**
     * Remove the specified supplier from storage.
     * Deletes a supplier by ID using SupplierService and returns a success response if successful.
     *
     * @param int $id Supplier ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($this->supplierService->delete($id)) {
            return $this->ok("Supplier deleted successfully");
        }
        return $this->error("Supplier not found", 404);
    }
}
