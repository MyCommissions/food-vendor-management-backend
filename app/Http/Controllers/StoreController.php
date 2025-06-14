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
        $user = Auth::user();
      
        $stores = $this->storeService->allStores($user);

        return response()->json([
            'stores' => $stores
        ], 200);
    }

    public function getOwnStore()
    {
        $user = Auth::user();

        $store = $this->storeService->store( $user);

        return response()->json([
            'store' => $store
        ]);
    }

    public function createStore(CreateStoreRequest $request)
    {
        $user = Auth::user();

        $store = $this->storeService->createStore($request->validated(), (int) $user->id, $user);

        return response()->json([
            'message' => 'Store Created Successfully!',
            'store' => [
                'id' => $store->user_id,
                'business_name' => $store->business_name,
                'business_type' => $store->business_type,
            ]
        ], 201);
    }

    public function updateOwnStore(UpdateStoreRequest $request, $storeId)
    {
        $user = Auth::user();

        $updatedStore = $this->storeService->updateStore($request->validated(), (int) $storeId, $user);

        return response()->json([
            'store' => $updatedStore
        ]);
    }
}
