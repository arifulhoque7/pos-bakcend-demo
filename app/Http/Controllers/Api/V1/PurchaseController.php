<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\V1\PurchaseService;
use App\Http\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PurchaseResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\V1\CreatePurchaseRequest;
use App\Http\Requests\Api\V1\UpdatePurchaseRequest;

/**
 * Class PurchaseController
 * Handles HTTP requests related to purchase operations and delegates business logic to the PurchaseService.
 */
class PurchaseController extends Controller
{
    use ApiResponses;

    /**
     * @var PurchaseService The service handling purchase data processes
     */
    protected $purchaseService;

    /**
     * PurchaseController constructor.
     * Injects PurchaseService to manage purchase-related business logic.
     *
     * @param PurchaseService $purchaseService
     */
    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * Display a listing of all purchases.
     * Retrieves all purchases from the database using PurchaseService and returns them as a resource collection.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $purchases = $this->purchaseService->findAll();
        return PurchaseResource::collection($purchases);
    }

    /**
     * Display the specified purchase.
     * Retrieves a single purchase by ID using PurchaseService and returns it as a purchase resource.
     *
     * @param int $id Purchase ID
     * @return PurchaseResource
     */
    public function show(int $id)
    {
        try {
            $purchase = $this->purchaseService->findOne($id);
            return $this->success("Purchase found", new PurchaseResource($purchase), 200);
        } catch (\Exception $e) {
            return $this->error("Purchase not found", 404);
        }
    }

    /**
     * Store a newly created purchase in storage.
     * Validates the request, creates a purchase using PurchaseService, and returns the purchase as a resource.
     *
     * @param CreatePurchaseRequest $request
     * @return PurchaseResource
     */
    public function store(CreatePurchaseRequest $request)
    {
        try {
            $data = $request->validated();
            $purchase = $this->purchaseService->create($data);
            return $this->success("Purchase created successfully", new PurchaseResource($purchase), 201);
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    /**
     * Update the specified purchase in storage.
     * Validates the request, updates the purchase using PurchaseService, and returns the updated purchase as a resource.
     *
     * @param UpdatePurchaseRequest $request
     * @param int $id Purchase ID
     * @return PurchaseResource
     */
    public function update(UpdatePurchaseRequest $request, int $id)
    {
        $data = $request->validated();
        try {
            $purchase = $this->purchaseService->update($id, $data);
            return $this->success("Purchase updated successfully", new PurchaseResource($purchase), 200);
        } catch (\Exception $e) {
            return $this->error("Purchase not found", 404);
        }
    }

    /**
     * Remove the specified purchase from storage.
     * Deletes a purchase by ID using PurchaseService and returns a success response if successful.
     *
     * @param int $id Purchase ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->purchaseService->delete($id);
            return $this->success("Purchase deleted successfully", null, 204);
        } catch (\Exception $e) {
            return $this->error("Purchase not found", 404);
        }
    }
}
