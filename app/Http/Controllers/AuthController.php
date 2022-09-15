<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        // $input = $request->all();
   
        $validator = Validator::make($data, [
            'email' => 'required',
            'password' => 'required'
        ]);
   
        if($validator->fails()){
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);    
        }

        if (Auth::attempt($data)) {
            // $token = Auth::user()->createToken('AuthApp')->accessToken;
            $user = Auth::user(); 
            $token =  $user->createToken('AuthApp')->accessToken; 

            return response()->json([
                'success' => true,
                'message' => 'User login successfully',
                'token' => $token,
            ], 200);
        } else {
            return response()->json(['message' => 'Unauthorised'], 401);
        }
    }   

    public function logout(Request $request){

        // $user = Auth::user();
        // $access_token = auth()->user()->token();
        // dd(Auth::user());

        if (Auth::check()) {
            $token = Auth::user()->token();
            $token->revoke();
            $token->delete();

            return response()->json([
                'success' => true,
                'message' => 'User is logout successfully',
            ], 200);
        } 
        else{ 
            return response()->json(['message' => 'Unauthorised'], 401);
        } 
    }
}
