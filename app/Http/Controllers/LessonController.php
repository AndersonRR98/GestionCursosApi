<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use Illuminate\Http\Request;


class LessonController extends Controller
{
    public function index()
    {
        $lesson = Lesson:: //with('users','category')
         included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($lesson);
        
    }

    public function store(Request $request)
    {
               $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            ]);

        $lesson = Lesson::create($request->all());
        return response()->json($lesson, 201);
    }

   public function show($id)
    {
        $lesson = Lesson::with(['course','comments'])->findOrFail($id);
        return response()->json($lesson);
    }

    public function update(Request $request, Lesson $lesson)
    {
          $request->validate([
             'titulo' => 'required|string|max:255',
            'contenido' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',         
        ]);

        $lesson->update($request->all());
        return response()->json($lesson);
        
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return response()->json(['message' => 'Deleted successfully']);
        
    }
}
