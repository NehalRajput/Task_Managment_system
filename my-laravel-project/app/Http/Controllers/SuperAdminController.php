<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $users = User::with('userType')->get();
        return view('super_admin.dashboard', compact('users'));
    }

    public function manageUsers()
    {
        $users = User::with('userType')->get();
        return view('super_admin.manage_users', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('super_admin.edit_user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,admin,intern',
            'password' => 'nullable|min:6|confirmed'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('super_admin.manage_users')
            ->with('success', 'User updated successfully');
    }

    public function deleteUser(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete super admin account');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
} 