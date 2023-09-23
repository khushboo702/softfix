<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserPostResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRequest $request)
    {
        try {
            if ($request->password == $request->confirm_password) {
                $users = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'number' => $request->phone_number,
                    'password' => Hash::make($request->password),
                ];
                User::create($users);
                return response()->json(['status' => true, 'message' => 'User registered successfully'], 201);
            } else {
                return $this->returnResponse($status = false, $code = 422, "Passwords doesn't match.", "", $action = json_decode('{}'));
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed. Please try again.'], 500);
        }
    }

// Login
    public function login(LoginRequest $request)
    {
        try {
            $password = Hash::make($request->password);

            $userEmail = User::where(['email' => $request->email])->first();
            if ($userEmail) {
                if (User::where(['email' => $request->email, 'password' => $password])) {
                    $tokenobj = $userEmail->createToken('name');
                    $token = $tokenobj->accessToken;
                    $success['token'] = $token;
                    $success['id'] = $userEmail->id;
                    $success['name'] = $userEmail->name;
                    $success['email'] = $userEmail->email;
                    $success['number'] = $userEmail->number;
                    $success['profile_image'] = $userEmail->profile_image ? url('upload/profile_image/') . '/' . $userEmail->profile_image : null;
                    if ($userEmail->status != 'active') {
                        return response()->json(['status' => true, 'message' => 'Your account is inactive'], 201);
                    }
                    return response()->json(['status' => true, 'message' => 'User login successfully.', 'data' => $success], 201);

                } else {
                    return response()->json(['error' => 'Invalid credentials'], 401);
                }

            } else {
                return response()->json(['error' => 'User not found'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed. Please try again.'], 500);
        }
    }

    public function userPost()
    {
        $user_id = auth('api')->user()->id;
        $userPost = User::with('postData')->where(['status' => 'active', 'id' => $user_id, 'deleted_at' => null])->get();
        if (count($userPost) > 0) {
            $userPostData = UserPostResource::collection($userPost);
            return response()->json(['status' => true, 'message' => "Data Fetched Successfully.", 'data' => $userPostData]);
        } else {
            return response()->json(['status' => false, 'message' => "No Record Found."]);

        }
    }

}
