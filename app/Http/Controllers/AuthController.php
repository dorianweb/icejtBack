<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserRessource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/** 
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )**/
class AuthController extends Controller
{
    /** 
     *@OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="admin@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="admin"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     ),
     * @OA\Response(
     *    response=201,
     *    description="token + user available ",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     **/
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user  = User::where('email', $request->email)->with('roles')->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            'user' => new UserRessource($user),
            'token' => $token
        ];

        return response($response, 201);
    }


    /** 
     *@OA\Post(
     * path="/api/logout",
     * summary="Logout route",
     * description="Logout with token user",
     * operationId="authLogout",
     * security={{"bearer_token":{}}},
     * tags={"auth"},
     * 
     * @OA\Response(
     *    response=404,
     *    description="user not logged in or token does not exist",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="U might not be logged in")
     *        )
     *     ),
     * @OA\Response(
     *    response=201,
     *    description="token delted",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="token deleted")
     *        )
     *     )
     * )
     **/
    public function logout(Request $request)
    {
        if ($request->user()) {
            $user = $request->user();
            $user->tokens()->delete();
            $response = ['message' => $user->name . ' tokens deleted'];
            return response($response, 200);
        } else {
            return response([
                'message' => 'user not logged in'
            ], 404);
        }
    }
}
