<?php

namespace App\Services;

use App\Exceptions\StoreNotFoundException;
use App\Exceptions\VendorOnlyAccessException;
use App\Http\Requests;
use App\Models\Store;
use App\Models\User;
use App\Exceptions\UnauthorizedAccessException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StoreService
{

    public function allStores(User $user)
    {
        if (!$user) {
            throw new UnauthorizedAccessException();
        }

        return Store::all();
    }

    public function store(User $user)
    {
        if (!$user) {
            throw new UnauthorizedAccessException();
        }
        
        return Store::where('user_id', $user->id)->firstOrFail();
    }

    public function createStore(array $data, User $user)
    {
        if (!$user->isAdmin() || !$user->isVendor()) {
            throw new UnauthorizedAccessException();
        }

        $store = Store::where("user_id", $user->id)->exists();

        if ($store) {
            throw new VendorOnlyAccessException();
        }

        return Store::create([
            "user_id" => $user->id,
            "business_name" => $data["business_name"],
            "business_type" => $data["business_type"],
        ]);
    }

    public function updateStore(array $data, int $storeId, User $user)
    {
        if (!$user->isAdmin() || !$user->isVendor()) {
            throw new UnauthorizedAccessException();
        }

        $store = Store::where('id', $storeId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $store->update($data);

        return $store;
    }
  
    public function deleteStore(User $user)
    {
        if (!$user->isAdmin() || !$user->isVendor()) {
            throw new UnauthorizedAccessException();
        }

        $storeId = Store::where('user_id', $user->id)
            ->firstOrFail();

        $store = Store::where('store_id', $storeId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $store->delete();

        return $store;
    }

    public function storesByBusinessType(string $businessType, User $user)
    {
        if (!$user->isAdmin()) {
            throw new UnauthorizedAccessException();
        }

        $stores = Store::where('business_type', $businessType)
            ->where('user_id', $user->id)
            ->get();

        if (!$stores) {
            throw new StoreNotFoundException();
        }

        return $stores;
    }
}
