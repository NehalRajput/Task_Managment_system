<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('role')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['admin', 'super_admin'])->get();
        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('password', 'profile_image');
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')
                ->store('admin-profiles', 'public');
        }

        Admin::create($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin created successfully');
    }

    public function edit(Admin $admin)
    {
        $roles = Role::whereIn('name', ['admin', 'super_admin'])->get();
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048'
        ]);

        $data = $request->except(['password', 'profile_image']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            if ($admin->profile_image) {
                Storage::disk('public')->delete($admin->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')
                ->store('admin-profiles', 'public');
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin updated successfully');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->isSuperAdmin()) {
            return back()->with('error', 'Cannot delete super admin account');
        }

        if ($admin->profile_image) {
            Storage::disk('public')->delete($admin->profile_image);
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin deleted successfully');
    }

    public function dashboard()
    {
        // Add dashboard logic here
        $tasks = Task::with('interns')->get();
        $interns = User::all();
        return view('Admin.Dashboard', [
            'tasks' => $tasks,
            'interns' => $interns
        ]);
    }
    public function deleteUser(User $user)
    {
        // Check if the user is an intern
     

        // Delete the user
        $user->delete();

        return redirect()->back()->with('success', 'Intern account deleted successfully');
    }
} 