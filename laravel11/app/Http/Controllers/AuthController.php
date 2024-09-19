<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'full_name' => 'required',
            'username' => 'required|min:3|unique:users,username|regex:/[A-Za-z._]+$/',
            'password' => 'required|min:6'
        ]);

        if($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field(s) in request',
                'errors' => $validate->errors()
            ], 400);
        }

        $data = $request->all();

        $user = User::create($data);

        $token = $user->createToken('user')->plainTextToken;

        $user['token'] = $token;

        $user['role'] = 'user';

        return response()->json([
            'status' => 'success',
            'message' => 'User registration successful',
            'data' => $user 
        ], 201);
    }

    public function login(Request $request) {
        $validate = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
        
        if($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field(s) in request',
                'errors' => $validate->errors()
            ], 400);
        }

        $data = $request->all();

        if(!Auth::validate($data)) {
            return response()->json([
                'status' => 'authentication_failed',
                'message' => 'The username or password you entered is incorrect'
            ], 400);
        }

        $user = User::firstWhere('username', $data['username']);

        if(!$user) {
            $user = Administrator::firstWhere('username', $data['username']);
            $token = $user->createToken('admin')->plainTextToken;
            $user['role'] = 'admin';
        } else {
            $token = $user->createToken('user')->plainTextToken;
            $user['role'] = 'user';
        }

        $user['token'] = $token;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => $user->only([
                'id',
                'username',
                'created_at',
                'updated_at',
                'token',
                'role'
            ])
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "status" => "success",
            "message" => "Logout successful"
        ], 200);
    }
}