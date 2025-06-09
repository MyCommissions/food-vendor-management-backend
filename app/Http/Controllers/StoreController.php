<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreRequest;
use App\Services\StoreService;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services;

class StoreController extends Controller
{
    protected $storeService;
    public function __construct(StoreService $storeService)
    {
        $this->$storeService = $storeService;
    }

    public function createStore(CreateStoreRequest $request)
    {
        $store = $this->storeService->createStore($request->validated());

        return response()->json([
            'message' => 'Store Created Successfully!',
            'store' => [
                'id' => $store->id,
                'business_name' => $store->business_name,
                'business_type' => $store->business_type,
            ]
        ]);
    }
}
