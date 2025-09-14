<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Http\Request;

class CourseController extends Controller
{
   public function index()
    {
        $course = Course:: //with('users','category')
         included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($course);
        
    }

    public function store(Request $request)
    {
               $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'instructor_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id']);

        $course = Course::create($request->all());
        return response()->json($course, 201);
    }

   public function show($id)
    {
        $course = Course::with(['users','category'])->findOrFail($id);
        return response()->json($course);
    }

    public function update(Request $request, Course $course)
    {
          $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric',
            'instructor_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id'          
        ]);

        $course->update($request->all());
        return response()->json($course);
        
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(['message' => 'Deleted successfully']);
        
    }
}
