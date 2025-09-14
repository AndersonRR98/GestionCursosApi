<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   
    public function index()
    {
        $category = Category::with('courses')
        ->included()
        -> filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($category);
        
    }

    public function store(Request $request)
    {
               $request->validate([
                'nombre' => 'required|string|max:255',
        ]);

        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

   public function show($id)
    {
        $category = Category::with(['courses'])->findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
          $request->validate([
            'nombre' => 'sometimes|string|max:255',
          
        ]);

        $category->update($request->all());
        return response()->json($category);
        
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Deleted successfully']);
        
    }
}
