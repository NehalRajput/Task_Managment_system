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
        $admins = Admin::all();
        return view('Admin.index', compact('admins'));
    }

    public function create()
    {
        return view('Admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role_id'] = 2;
        Admin::create($validated);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin created successfully.');
    }

    public function edit(Admin $admin)
    {
        return view('Admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $validated['role_id'] = 2;
        $admin->update($validated);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin deleted successfully.');
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