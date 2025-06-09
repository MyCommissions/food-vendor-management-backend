<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminOnly;

class AdminController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware(AdminOnly::class);
        $this->userService = $userService;
    }

    public function getAllUsers()
    {
        $users = $this->userService->allUsers();

        return response()->json([
            "users" => $users
        ]);
    }

    public function getAllVendors()
    {
        $vendors = $this->userService->allVendors();
        
        return response()->json([
            "users" => $vendors
        ]);
    }

    public function getAllCustomers()
    {
        $customers = $this->userService->allCustomers();

        return response()->json([
            "users" => $customers
        ]);
    }

    public function getPendingVendors(): JsonResponse
    {
        $pendingVendors = $this->userService->pendingVendors();

        return response()->json([
            'pending_vendors' => $pendingVendors
        ]);
    }

    public function approveVendor(User $user): JsonResponse
    {
        $this->userService->approveVendor($user);

        return response()->json([
            'message' => 'Vendor approved successfully',
            'user' => $user->fresh()
        ]);
    }

    public function rejectVendor(User $user): JsonResponse
    {
        $this->userService->rejectVendor($user);

        return response()->json([
            'message' => 'Vendor rejected and removed from the system'
        ]);
    }
}
