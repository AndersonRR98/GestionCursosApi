<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreroleRequest;
use App\Http\Requests\UpdateroleRequest;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role:: //with('users','category')
         included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($role);
        
    }

    public function store(Request $request)
    {
             $request->validate([
            'nombre' => 'required|string|max:255',
            'permisos' => 'required|string|max:255',
            ]);

        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

   public function show($id)
    {
        $role = Role::with(['users'])->findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, Role $role)
    {
          $request->validate([
             'nombre' => 'required|string|max:255',
            'permisos' => 'required|string|max:255',        
        ]);

        $role->update($request->all());
        return response()->json($role);
        
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Deleted successfully']);
        
    }
}


