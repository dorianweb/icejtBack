<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\User;
use App\Models\Flavor;
use App\Models\Supplement;
use App\Models\CustomCream;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomCreamController extends Controller
{

    //apply middleware
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'destroy', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //CustomCream::factory()->has(Flavor::factory()->count(2))->has(Supplement::factory()->count(3))->count(10)->create();
        $creams = CustomCream::with('flavors', 'supplements.supplement_type')->orderBy('created_at')->paginate(10);
        return response($creams, 200);
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
            $response = ['created' => false];
            $code = 404;
            $data = $request->validate([
                'name' => 'required|string',
                'flavors' => 'required|array',
                'supplements' => ['required', 'array']
            ]);
            $response['validation'] = true;
            $cream =  CustomCream::firstOrCreate([
                'name' => $data['name']
            ]);
            $cream->flavors()->sync($data['flavors']);
            $cream->supplements()->sync($data['supplements']);
            $cream->save();

            $carts = $request->user()->carts;
            if ($carts) {
                $currentCart = $carts
                    ->where(['state' => Cart::STATE_CREATED, 'user_id' => $request->user()->id])
                    ->orderBy('created_at')
                    ->first();
            }
            // create a cart for user who dont have one
            if (!$carts || !$currentCart) {
                $currentCart = Cart::create([
                    'state' => Cart::STATE_CREATED,
                    'user_id' => $request->user()->id
                ]);
            }
            $currentCart->custom_creams()->attach($cream);
            $currentCart->save();
            $code = 201;
            $response['created'] = true;
            $response['data'] = $cream->load('supplements', 'flavors', 'carts');
        } catch (ValidationException $e) {
            return $e;
            $response = [
                'created' => false,
                'validation' => false,
            ];
            $code = 404;
        }
        return response($response, $code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CustomCream::findOrFail($id)->load('carts');
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
        try {
            $response = ['updated' => false];
            $code = 404;
            $data = $request->validate([
                'name' => 'required|string',
                'flavors' => 'required|array',
                'supplements' => ['required', 'array']
            ]);

            $response['validation'] = true;
            $cream = CustomCream::with('flavors', 'supplements')->find($id);
            if ($cream) {
                $cream->name = $data['name'];
                $cream->flavors()->sync($data['flavors']);
                $cream->supplements()->sync($data['supplements']);
                $cream->save();
                $code = 200;
                $response['updated'] = true;
                $response['data'] = $cream;
            }
        } catch (ValidationException $e) {
            return $e;
            $response = [
                'updated' => false,
                'validation' => false,
            ];
            $code = 404;
        }
        return response($response, $code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // if ($request->user()->hasRole(User::ROLE_ADMIN)) { not working on production for no available reason
        if ($request->user()) {
            $cream = CustomCream::with('carts')->findOrFail($id);
            $carts = $cream->carts();
            $cream->carts()->detach($cream->id);
            $isDeleted = $cream->delete();
            return  response(['deleted' => $isDeleted], $isDeleted ? 200 : 404);
        }
    }
}
