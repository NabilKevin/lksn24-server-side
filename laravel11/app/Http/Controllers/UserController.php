<?php

namespace App\Http\Controllers;

use App\Models\CompletedLesson;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request, $slug) {
        $course = Course::firstWhere('slug', $slug);

        if(!$course) {
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            ], 404);
        }

        $enrollment = Enrollment::where('course_id', $course->id)->firstWhere('user_id', $request->user()->id);

        if($enrollment) {
            return response()->json([
                "status" => "error",
                "message" => "The user is already registered for this course"            
            ], 400);
        }

        Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $request->user()->id
        ]);
        
        return response()->json([
            "status" => "success",
            "message" => "User registered successful"          
        ], 201);
    }

    public function progress(Request $request) {
        $coursesId = Enrollment::where('user_id', $request->user()->id)->get()->pluck('course_id');
        
        $courses = Course::whereIn('id', $coursesId)->get();
        
        $lessonId = CompletedLesson::where('user_id', $request->user()->id)->get()->pluck('lesson_id');

        $lessons = Lesson::whereIn('id', $lessonId)->get();

        return response()->json([
            "status" => "success",
            "message" => "User progress retrieved successfully",
            "data" => [
                'progress' => [
                    'courses' => $courses,
                    'completed_lessons' => $lessons->only(['id', 'name', 'order'])
                ]
            ]
        ], 201);
    }
}
