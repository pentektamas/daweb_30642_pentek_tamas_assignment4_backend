<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string|confirmed',
            'username' => 'required|string|unique:users',
            'email' => 'required|string'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            //'isDoctor' => false,
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = request(['username', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        return response()->json([
            'message' => 'Authorized',
            'isDoctor'=>$user->isDoctor,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse [json] user object
     */
    public function user(Request $request)
    {


        return response()->json($request->user());
    }

    public function update(Request $request)
    {

        //'pasword'=>bcrypt($request->password),
        DB::table('users')->where('id',$request->id)->update(array(
            'name'=>$request->name,
            'email'=>$request->email,
            'username'=>$request->username,
            'medicalServices'=>$request->medicalServices,
            'gdpr'=>$request->gdpr,
            ));

        if ($request->password !=null){
            DB::table('users')->where('id',$request->id)->update(array(
                'password'=>bcrypt($request->password),
            ));
        }

        return response()->json([
            'message' => $request->id
        ]);
    }
}
