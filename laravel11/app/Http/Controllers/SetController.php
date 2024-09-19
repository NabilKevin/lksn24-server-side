<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SetController extends Controller
{
    public function store(Request $request, $slug) {
        $course = Course::firstWhere('slug', $slug);

        if(!$course) {
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            
            ], 404);
        }

        if(!$course) {
            return response()->json([

            ], );
        }
        
        $validate = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field(s) in request',
                'errors' => $validate->errors()
            ], 400);
        }

        $data = $request->all();

        $count = Count(Set::where('course_id', $course->id)->get());

        $set = Set::create([
            'name' => $data['name'],
            'order' => $count + 1,
            'course_id' => $course->id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Set successfully added',
            'data' => $set
        ], 201);
    }

    public function destroy($slug, $id) {
        $course = Course::firstWhere('slug', $slug);

        if(!$course) {
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            
            ], 404);
        }

        $set = Set::find($id);
        
        $set->delete();

        return response()->json([
            "status" => "success",
            "message" => "Set successfully deleted"        
        ], 200);

    }
}
