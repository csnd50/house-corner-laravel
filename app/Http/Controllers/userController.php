<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PhpParser\Parser\Tokens;

class userController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'Required|string',
            'email' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'],

        ]);
        $token = $user->createToken('secert_key')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens->each->delete();
        return [
            'message' => 'logged out',
        ];
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'wrong email or passwprd ',
                404
            ]);
        }
        $token = $user->createToken('secert_key')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }
    public function userInfo(Request $request)
    {
        $user_Id = $request->user()->id;
        $user = User::find($user_Id);
        return response()->json([$user]);
    }
    public function update(Request $request)
    {
       var_dump($request->name,$request->password);
        $request->validate([
            'name' => 'required|string',
        ]);
    
        $user_Id = $request->user()->id;
        $user = User::find($user_Id);
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Update the user's name
        $user->name = $request->input('name');
        $user->save();
    
        return response()->json(['Message' => 'Name updated', 'user' => $user]);
    }
    
}
