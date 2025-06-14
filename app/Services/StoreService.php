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

    public function allStores()
    {
        return Store::all();
    }

    public function store($userId)
    {
        return Store::where('user_id', $userId)->get();
    }

    public function createStore(array $data, int $userId): Store
    {
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

    public function updateStore(array $data, int $storeId, int $userId)
    {
        if ($storeId && !$userId) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'You are unauthorized to update this store.'
                ], 403)
            );
        }

        if (!$userId) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'User does not exist.'
                ], 404)
            );
        }

        $updatedStore = Store::where('id', $storeId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if (!$updatedStore) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Store does not exist.'
                ], 404)
            );
        }

        $updatedStore->update($data);

        return $updatedStore;
    }
}
