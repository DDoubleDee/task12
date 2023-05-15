<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], $messages = Controller::validatorMessages());
        if ($validator->fails()) {
            return Controller::convertValidator($validator);
        }
        $user = User::where('username', $request->input('username'))->get()->first();
        if (is_null($user)) {
            return response(['message' => 'Invalid credentials'], 400)->header('Content-Type', 'application/json');
        }
        if ($request->input('password') != $user->password) {
            return response(['message' => 'Invalid credentials'], 400)->header('Content-Type', 'application/json');
        }
        if ($user->accessToken != "") {
            return response(['message' => 'User already authenticated'], 403)->header('Content-Type', 'application/json');
        }
        $token = Str::random(100);
        $user->accessToken = $token;
        $user->save();
        return response(["token" => $token], 200)->header('Content-Type', 'application/json');
    }

    public function logout(Request $request)
    {
        $user = User::where('accessToken', $request->bearerToken())->get()->first();
        $user->accessToken = "";
        $user->save();
        return response(["message" => "logout successfully"], 200)->header('Content-Type', 'application/json');
    }
}
