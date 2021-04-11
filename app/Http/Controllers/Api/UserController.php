<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserRessource;
use Illuminate\Validation\ValidationException;

/** 
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )**/
class UserController extends Controller
{
    /** 
     *@OA\Get(
     * path="/api/users",
     * summary="user collection access route",
     * description="user collection",
     *operationId="getUserList",
     * security={{}},
     * tags={"user"},
     * @OA\Response(
     *    response=200,
     *    description="request completed",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="string", example="token deleted")
     *        )
     *     )
     * )
     **/
    public function index()
    {
        return UserRessource::collection(User::with('roles')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'name' => 'nullable',
                'password' => ['required'],
            ]);
            $user = new User();
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->name = $request->name == null ? null : $request->name;
            $user->save();
            $user->roles()->attach(Role::where(['name' => User::ROLE_USER])->first());
            $user->save();
            $code = 201;
            $response['created'] = true;
            $response['data'] = $user;
        } catch (ValidationException $e) {
            $response['created'] = false;
            $code = 404;
        }
        return response($response, $code);
    }

    /**
     * @OA\Get(
     *      path="/api/users/{id}",
     *      operationId="getProjectById",
     *      tags={"user"},
     *      summary="Get user information ",
     *      description="Returns user complete data",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *     )
     *  ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function show($id)
    {
        return User::with('roles', 'carts.classic_creams.flavor', 'carts.custom_creams.supplements.supplement_type', 'carts.custom_creams.flavors')->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'nullable',
        ]);
        $user = User::find($id);
        $user2 = User::where(['email' => $request->email])->first();
        if ($user) {

            if ((!is_null($user2) && $user->id == $user2->id) || (is_null($user2) && !is_null($user->id))) {
                $user->email =  $request->email;
            } else {
                $code = 404;
                $result = 'email already exist';
            }
            if ($user->password != $request->password && $user->password != bcrypt($request->password)) {
                $user->password = bcrypt($request->password);
            }
            $user->name = $request->name;
            $result = $user;
            $user->update();

            $code = 200;
        }
        return response($result, $code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *      path="/api/users/{id}",
     *      operationId="deleteUserById",
     *      tags={"user"},
     *      summary=" Soft delete user",
     *      description="Returns ",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="boolean"),
     *     )
     *  ),
     *     @OA\Response(
     *     response=404,
     *     description="Error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="boolean"),
     *     )
     *  ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $isDeleted = $user->delete();
        return  response(['deleted' => $isDeleted], $isDeleted ? 200 : 404);
    }
}
