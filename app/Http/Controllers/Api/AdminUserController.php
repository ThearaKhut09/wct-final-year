<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')
                      ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Show specific user
     */
    public function show($id)
    {
        $user = User::with('customer')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Create a new user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,customer',
            'phone' => 'nullable|string',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now()
        ]);

        // Create customer profile if role is customer
        if ($request->role === 'customer') {
            Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'user_id' => $user->id
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $user->load('customer'),
            'message' => 'User created successfully'
        ], 201);
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|string|min:8',
            'role' => 'sometimes|required|in:admin,customer',
            'phone' => 'nullable|string',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $userData = $request->only(['name', 'email', 'role']);

        if ($request->has('password') && $request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update customer profile if exists
        if ($user->customer && $request->role === 'customer') {
            $user->customer->update([
                'name' => $request->name ?? $user->customer->name,
                'email' => $request->email ?? $user->customer->email,
                'phone' => $request->phone ?? $user->customer->phone,
                'address' => $request->address ?? $user->customer->address
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $user->load('customer'),
            'message' => 'User updated successfully'
        ]);
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Prevent deleting the current admin user
        if ($user->id === Auth::user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account'
            ], 400);
        }

        // Delete customer profile if exists
        if ($user->customer) {
            $user->customer->delete();
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User status updated successfully'
        ]);
    }

    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $activeUsers = User::where('is_active', true)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'total_admins' => $totalAdmins,
                'total_customers' => $totalCustomers,
                'active_users' => $activeUsers
            ]
        ]);
    }
}
