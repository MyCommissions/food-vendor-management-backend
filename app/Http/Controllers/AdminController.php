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
        try {
            $users = $this->userService->allUsers();

            return response()->json([
                "users" => $users
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getAllVendors()
    {
        try {
            $vendors = $this->userService->allVendors();

            return response()->json([
                "users" => $vendors
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getAllCustomers()
    {
        try {
            $customers = $this->userService->allCustomers();

            return response()->json([
                "users" => $customers
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getUser($id)
    {
        try {
            $validatedRequest = validator([
                'user_id' => $id
            ], [
                'user_id' => 'required|exists:users,id'
            ])->validate();

            $user = $this->userService->user($validatedRequest['user_id']);

            return response()->json([
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getPendingVendors(): JsonResponse
    {
        try {
            $pendingVendors = $this->userService->pendingVendors();

            return response()->json([
                'pending_vendors' => $pendingVendors
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function approveVendor(User $user): JsonResponse
    {
        try {
            $this->userService->approveVendor($user);

            return response()->json([
                'message' => 'Vendor approved successfully',
                'user' => $user->fresh()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function rejectVendor(User $user): JsonResponse
    {
        try {
            $this->userService->rejectVendor($user);

            return response()->json([
                'message' => 'Vendor rejected and removed from the system'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
