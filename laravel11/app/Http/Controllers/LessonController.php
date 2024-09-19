<?php

namespace App\Http\Controllers;

use App\Models\CompletedLesson;
use App\Models\Lesson;
use App\Models\LessonContent;
use App\Models\Option;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function store(Request $request) {
        $rule = [
            'name' => 'required',
            'set_id' => 'required',
            'contents' => 'array',
            'contents.*.type' => 'in:learn,quiz',
            'contents.*.content' => 'required'
        ];
        

        $validate = Validator::make($request->all(), $rule);
        
        if($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field(s) in request',
                'errors' => $validate->errors()
            ], 400);
        }

        foreach($request->contents as $i => $val) {
            if($val['type'] === 'quiz') {
                $rule["contents.$i.options.*.option_text"] = 'required';
                $rule["contents.$i.options.*.is_correct"] = 'boolean';
            }
        }

        $validate = Validator::make($request->all(), $rule);
        
        if($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field(s) in request',
                'errors' => $validate->errors()
            ], 400);
        }

        $data = $request->all();
        
        $set = Set::find($data['set_id']);

        if(!$set) {
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            ], 404);
        }

        $lessons = Count(Lesson::where('set_id', $set->id)->get());

        $lesson = Lesson::create([
            'set_id' => $set->id,
            'name' => $data['name'],
            'order' => $lessons + 1
        ]);

        
        foreach($data['contents'] as $content) {
            $lessonContents = Count(LessonContent::where('lesson_id', $lesson->id)->get());
            $lessonContent = LessonContent::create([
                'lesson_id' => $lesson->id,
                'type' => $content['type'],
                'content' => $content['content'],
                'order' => $lessonContents + 1
            ]);

            if($content['type'] === 'quiz') {
                foreach($content['options'] as $option) {
                    Option::create([
                        'lesson_content_id' => $lessonContent->id,
                        'option_text' => $option['option_text'],
                        'is_correct' => $option['is_correct']
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson successfully added',
            'data' => $lesson->only(['name', 'order', 'id'])
        ], 201);

    }

    public function destroy($id) {
        $lesson = Lesson::find($id);

        if(!$lesson) {
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            ], 404);
        }

        $lesson->delete();

        return response()->json([
            "status" => "success",
            "message" => "Lesson successfully deleted"
        ], 200);
    }

    public function checkAnswer(Request $request, $id) {
        $validate = Validator::make($request->all(), [
            'option_id' => 'required'
        ]);
        
        if($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field(s) in request',
                'errors' => $validate->errors()
            ], 400);
        }

        $data = $request->all();

        $lessonContent = LessonContent::find($id);

        $option = Option::find($data['option_id']);

        if(!$option || !$lessonContent) {
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            ], 404);
        }

        if($lessonContent->type !== 'quiz') {
            return response()->json([
                "status" => "error",
                "message" => "Only for quiz content"            
            ], 400);
        }

        return response()->json([
            "status" => "success",
        "message" => "Check answer success",
            "data" => [
                'question' => $lessonContent->content,
                'user_answer' => $option->option_text,
                'is_correct' => $option->is_correct
            ]
        ]);
    }

    public function completeLesson(Request $request,$id) {
        $lesson = Lesson::find($id);
        if(!$lesson) {
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            ], 404);
        }

        $complete = CompletedLesson::where('lesson_id', $id)->firstWhere('user_id', $request->user()->id);

        if(!$complete) {
            CompletedLesson::create([
                'lesson_id' => $id,
                'user_id' => $request->user()->id
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Lesson successfully completed"
        ], 200);
    }


}
