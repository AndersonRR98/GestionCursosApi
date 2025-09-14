<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;


class UserController extends Controller
{
    
    public function index()
    {
        $user = User:: //with('users','category')
         included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($user);
        
    }

    public function store(Request $request)
    {
             $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'activo' => 'required|integer',
            'rol_id' => 'required|exists:roles,id',
            ]);

        $user = User::create($request->all());
        return response()->json($user, 201);
    }

   public function show($id)
    {
        $user = User::with(['role','comments','payments','courses'])->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
          $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'activo' => 'required|integer',
            'rol_id' => 'required|exists:roles,id',      
        ]);

        $user->update($request->all());
        return response()->json($user);
        
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Deleted successfully']);
        
    }
}
