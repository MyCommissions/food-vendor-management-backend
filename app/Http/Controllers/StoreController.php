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
        $stores = $this->storeService->allStores();

        return response()->json([
            'stores' => $stores
        ], 200);
    }

    public function getOwnStore()
    {
        $id = Auth::user()->id;

        $validatedStore = validator([
            'user_id' => $id,
        ],
        [
            'user_id' => 'required|exists:users,id'
        ])->validate();

        $store = $this->storeService->store($validatedStore['user_id']);

        return response()->json([
            'store' => $store
        ]);
    }

    public function createStore(CreateStoreRequest $request)
    {
        $userId = Auth::user()->id;

        $store = $this->storeService->createStore($request->validated(), $userId);

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
        $userId = Auth::user()->id;

        $updatedStore = $this->storeService->updateStore($request->validated(), $userId, $storeId);

        return response()->json([
            'store' => $updatedStore
        ]);
    }
}
