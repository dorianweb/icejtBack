<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Flavor;
use App\Models\ClassicCream;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ClassicCreamController extends Controller
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
        $creams = ClassicCream::with('flavor')->paginate();
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
        $code = 404;
        $response = ['created' => false];
        // if ($request->user()->hasRole(User::ROLE_ADMIN)) { not working on production for no available reason
        if ($request->user()) {
            try {
                $data = $request->validate([
                    'name' => 'required|string',
                    'description' => 'nullable|string',
                    'flavor' => 'required|integer',
                ]);
            } catch (ValidationException $error) {

                $response['message'] = $error;
                return response($response, $code);
            }
            $flavor = Flavor::find($data['flavor']);
            if ($flavor) {

                $cream = ClassicCream::firstOrNew([
                    'name' => $data['name'],
                    'description' => $data['description'],
                ]);
                $cream->flavor()->associate($flavor);
                $cream->save();
                $response['created'] = true;
                $response['data'] = $cream;
            }
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
        return ClassicCream::findOrFail($id);
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
                'description' => 'nullable|string',
                'flavor' => 'required|integer',
            ]);
            $response['validation'] = true;
            $cream = ClassicCream::with('flavor')->find($id);
            $newFlavor = Flavor::find($data['flavor']);
            $oldFlavor = Flavor::find($cream->flavor->id);

            if ($cream && $newFlavor && $oldFlavor) {
                $cream->name = $data['name'];
                $cream->description = $data['description'];
                $cream->flavor()->dissociate($oldFlavor);
                $cream->flavor()->associate($newFlavor);
                $cream->save();
                $code = 200;
                $response['updated'] = true;
                $response['data'] = $cream;
            }
        } catch (ValidationException $e) {
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
        //if ($request->user()->hasRole(User::ROLE_ADMIN)) { not working on production for no available reason
        if ($request->user()) {
            $cream = ClassicCream::with('carts')->findOrFail($id);
            $carts = $cream->carts();
            $cream->carts()->detach($cream->id);
            $isDeleted = $cream->delete();
            return  response(['deleted' => $isDeleted], $isDeleted ? 200 : 404);
        }
    }
}
