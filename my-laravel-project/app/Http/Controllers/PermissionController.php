<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->get();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'permission' => 'required|unique:permissions,permission',
            'description' => 'nullable|string'
        ]);

        Permission::create($request->all());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'permission' => 'required|unique:permissions,permission,' . $permission->id,
            'description' => 'nullable|string'
        ]);

        $permission->update($request->all());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->exists()) {
            return back()->with('error', 'Cannot delete permission that is assigned to roles');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
} 