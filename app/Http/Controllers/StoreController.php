<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Services\StoreService;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    protected $storeService;
    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function getAllStores()
    {
        try {
            $user = Auth::user();
            $stores = $this->storeService->allStores($user);

            return response()->json([
                'stores' => $stores
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getOwnStore()
    {
        try {
            $user = Auth::user();
            $store = $this->storeService->store($user);

            return response()->json([
                'store' => $store
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createStore(CreateStoreRequest $request)
    {
        try {
            $user = Auth::user();
            $store = $this->storeService->createStore($request->validated(), $user);

            return response()->json([
                'message' => 'Store Created Successfully!',
                'store' => [
                    'id' => $store->user_id,
                    'business_name' => $store->business_name,
                    'business_type' => $store->business_type,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateOwnStore(UpdateStoreRequest $request, $storeId)
    {
        try {
            $user = Auth::user();
            $updatedStore = $this->storeService->updateStore($request->validated(), (int) $storeId, $user);

            return response()->json([
                'store' => $updatedStore
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteOwnStore()
    {
        try {
            $user = Auth::user();
            $deletedStore = $this->storeService->deleteStore($user);

            return response()->json([
                'store' => $deletedStore
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storesByBusinessType($businessType)
    {
        try {
            $user = Auth::user();
            $stores = $this->storeService->storesByBusinessType((string) $businessType, $user);

            return response()->json([
                'stores' => $stores
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
