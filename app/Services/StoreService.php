<?php

namespace App\Services;

use App\Http\Requests;
use App\Models\Store;
use App\Models\User;

class StoreService
{
    public function createStore(array $data): Store
    {
        return Store::create([
            "user_id" => $data["user_id"],
            "business_name" => $data["business_name"],
            "business_type" => $data["business_type"],
        ]);
    }
}
