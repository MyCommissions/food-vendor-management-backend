<?php

namespace App\Services;

use App\Http\Requests;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StoreService
{
    public function createStore(array $data): Store
    {
        $user = Auth::user();

        return Store::create([
            "user_id" => $user["id"],
            "business_name" => $data["business_name"],
            "business_type" => $data["business_type"],
        ]);
    }
}
