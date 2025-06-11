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
}
