<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Mail\ConfirmEmail;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $validator = $request->validated();

        DB::beginTransaction();

        $validator['password'] = bcrypt($validator['password']);

        $user = User::create($validator);

        //TODO: TAKE OUT ACTIVE AND IS_VERIFIED FROM HERE
        $user->active = 1;
        //$user->is_verified = 1;
        $user->save();

        DB::commit();

        Mail::to($user->email)->send(new ConfirmEmail($user, $validator['name'])); //change mail

        return response()->json([
            'success' => true,
            'message' => 'Please verify your email to get access to Wave Labs dashboard'
        ]);
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');


        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()], 401);
        }

        $user = User::where('email', $request->email)->first();


        try {
            // attempt to verify the credentials and create a token for the user
            if (!$user->is_verified) {
                return response()->json(['success' => false, 'message' => 'The email is not verified.'], 401);
            }

            if (!$token = Auth::attempt($credentials)) {
                return response()->json(['success' => false, 'message' => 'We cant find an account with this credentials. Please make sure you entered the right information and you have verified your email address.'], 404);
            }
        } catch (Exception $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'message' => 'Failed to login, please try again.'], 500);
        }

        if (!$user->is_verified) {
            return response()->json(['success' => false, 'message' => 'The email is not verified.'], 401);
        }

        if (!$user->active) {
            return response()->json(['success' => false, 'message' => 'You must wait for our admin to accept your account.'], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'user' => new UserResource(Auth::user()),
        ]);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return response()->json(['success' => true, 'message' => "You have successfully logged out."]);
        } catch (Exception $e) {

            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }
}
