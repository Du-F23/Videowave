<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::User();
            $success ['token'] = $auth->createToken('LaravelSanctumAuth')->plainTextToken;
            return response()->json([
                'access_token' => $success,
                'user' => $auth,
                'message' => 'Login success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Login failed'
            ], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 401);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user= User::create($input);
        $success['token'] = $user->createToken('LaravelSanctumAuth')->plainTextToken;

        return response()->json([
            'access_token' => $success,
            'user' => $user,
            'message' => 'Register success'
        ], 201);
    }
}
