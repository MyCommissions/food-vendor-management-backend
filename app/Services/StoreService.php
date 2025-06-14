<?php

namespace App\Services;

use App\Http\Requests;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StoreService
{
    public function allStores(User $user)
    {
        if (!$user->isAdmin()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Unauthorized Access to this route.'
                ], 403)
            );
        }

        return Store::all();
    }

    public function store(User $user)
    {
        if (!$user->isVendor()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Unauthorized Access to this route.'
                ], 403)
            );
        }
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Store not found.'
                ], 404)
            );
        }

        return $store;
    }

    public function createStore(array $data, int $userId, User $user): Store
    {
        if ($user->isUser()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Only Vendors can create a store.'
                ], 403)
            );
        }

        $storeExists = Store::where("user_id", $userId)->exists();

        if ($storeExists) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Vendor already have a store registered on the system.'
                ], 403)
            );
        }

        return Store::create([
            "user_id" => $userId,
            "business_name" => $data["business_name"],
            "business_type" => $data["business_type"],
        ]);
    }

    public function updateStore(array $data, int $storeId, User $user)
    {
        if (!$user->isVendor()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Unauthorized Access to this route.'
                ], 403)
            );
        }

        $store = Store::where('id', $storeId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$store) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Store not found or you are not authorized to update it.'
                ], 404)
            );
        }

        $store->update($data);

        return $store;
    }

    public function deleteStore($storeId)
    {
        $store = Store::where('store_id', $storeId);
    }
}
