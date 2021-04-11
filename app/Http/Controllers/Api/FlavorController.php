<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Flavor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FlavorRessource;
use App\Http\Resources\FlavorCollection;
use Illuminate\Validation\ValidationException;

class FlavorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flavors = new FlavorCollection(Flavor::paginate());
        return response($flavors, 200);
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
        if ($request->user()->hasRole(User::ROLE_ADMIN)) {
            $data = $request->validate([
                'name' => 'distinct|string|unique:flavors,name',
                'price' => 'distinct|integer',
                'color' => 'distinct|string|unique:flavors,color',
            ]);
            $flavor = Flavor::firstOrCreate([
                'name' => $data['name'] . '1',
                'price' => $data['price'],
                'color' => $data['color'] . '1',
            ]);
            $response['created'] = true;
            $response['data'] = new FlavorRessource($flavor);
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
        return new FlavorRessource(Flavor::findOrFail($id));
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
                'name' => 'required|distinct|string|unique:flavors,name',
                'price' => 'required|distinct|integer',
                'color' => 'required|distinct|string|unique:flavors,color'
            ]);
            $response['validation'] = true;
            $flavor = Flavor::find($id);
            if ($flavor) {
                $flavor->name = $data['name'];
                $flavor->price = $data['price'];
                $flavor->color = $data['color'];
                $flavor->save();
                $code = 200;
                $response['updated'] = true;
                $response['data'] = new FlavorRessource($flavor);
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
    public function destroy($id)
    {
        $user = Flavor::findOrFail($id);
        $isDeleted = $user->delete();
        return  response(['deleted' => $isDeleted], $isDeleted ? 200 : 404);
    }
}
