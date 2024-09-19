<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function store(Request $request) {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:courses,slug'
        ]);

        if($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field(s) in request',
                'errors' => $validate->errors()
            ], 400);
        }
        
        $data = $request->all();

        $course = Course::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Course successfully added',
            'data' => $course
        ], 201);
    }

    public function update(Request $request, $slug) {
        $course = Course::firstWhere('slug', $slug);

        if(!$course) {
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            
            ], 404);
        }

        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'is_published' => 'boolean'
        ]);

        if($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field(s) in request',
                'errors' => $validate->errors()
            ], 400);
        }
        
        $data = $request->all();

        $course->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Course successfully added',
            'data' => $course
        ], 201);
    }

    public function destroy(Request $request, $slug) {
        $course = Course::firstWhere('slug', $slug);

        if(!$course) {
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            
            ], 404);
        }

        $course->delete();

        return response()->json([
            "status" => "success",
            "message" => "Course successfully deleted"        
        ], 200);

    }

    public function index() {
        $courses = Course::all();

        return response()->json([
            "status"=> "success",
            "message"=> "Courses retrieved successfully",
            "data"=> [
                "courses" => $courses
            ]
        ]);
    }
    public function show($slug) {
        $courses = Course::with('sets.lessons.contents')->firstWhere('slug', $slug);

        return response()->json([
            "status"=> "success",
            "message"=> "Course details retrieved successfully",
            "data"=> $courses
        ]);
    }
}
