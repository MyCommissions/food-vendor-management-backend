<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreRequest;
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
}
