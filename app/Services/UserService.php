<?php

namespace App\Services;

use App\Models\Store;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function register(array $data): User
    {
        $isVendor = $data['role_id'] === 2;
        
        return User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'birthday' => $data['birthday'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'is_approved' => !$isVendor, // Auto-approve regular users, vendors need admin approval
        ]);
    }

    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if vendor is approved
        if ($user->isVendor() && !$user->is_approved) {
            throw ValidationException::withMessages([
                'account' => ['Your vendor account is pending approval. Please wait for admin confirmation.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout(): void
    {
        $user = Auth::user();

        if (!Store::where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'account' => ['You need to create your store first']
            ]);
        }

        if ($user) {
            $user->currentAccessToken()->delete();
        }
    }

    public function approveVendor(User $user): void
    {
        if (!$user->isVendor()) {
            throw ValidationException::withMessages([
                'user' => ['This user is not a vendor.'],
            ]);
        }

        $user->update(['is_approved' => true]);
    }

    public function rejectVendor(User $user): void
    {
        if (!$user->isVendor()) {
            throw ValidationException::withMessages([
                'user' => ['This user is not a vendor.'],
            ]);
        }

        $user->delete();
    }

    public function pendingVendors()
    {
        return User::where('role_id', 2)
            ->where('is_approved', false)
            ->with(['store'])
            ->get();
    }

    public function allUsers()
    {
        return User::all();
    }

    public function allVendors()
    {
        return User::where('role_id', 2)->get();
    }

    public function allCustomers()
    {
        return User::where('role_id', 1)->get();
    }

    public function user(Int $request)
    {
        return User::where('id', $request)->firstOrFail();
    }
}
