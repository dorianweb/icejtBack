<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Supplement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupplementType;
use Illuminate\Validation\ValidationException;

class SupplementController extends Controller
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
        return Supplement::paginate();
    }

    /**s
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
                    'name' => ['required', 'string'],
                    'weight' => ['required', 'int'],
                    'unit' => ['required', 'string'],
                    'supplement_type' => ['required', 'string'],
                ]);
                $supplementType = SupplementType::find($data['supplement_type']);
                if (!$supplementType) {
                    $supplementType = SupplementType::where(['name' => $data['supplement_type']])->first();
                }
                if ($supplementType) {
                    $supplement = Supplement::firstOrNew([
                        'name' => $data['name'],
                        'weight' => $data['weight'],
                        'unit' =>
                        $supplementType->name === Supplement::TYPE_NAPPAGE ? Supplement::UNIT_LIQUID : $data['unit'],
                    ]);
                    $supplement->supplement_type()->associate($supplementType);
                    $supplement->save();
                    $response['created'] = true;
                    $response['data'] = $supplement;
                }
            } catch (ValidationException $error) {
                $response['message'] = $error;
                return response($response, $code);
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
        return Supplement::findOrFail($id);
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
        return response([
            'message' => 'method is disabled,please delete model instead ',
            'updated' => 'false',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * may delete relation with parent (here custom_creams)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // if ($request->user()->hasRole(User::ROLE_ADMIN)) { not working on production for no available reason
        if ($request->user()) {
            $supplement = Supplement::with('supplement_type')->findOrFail($id);
            $carts = $supplement->supplement_type()->dissociate($supplement->supplement_type);
            $isDeleted = $supplement->delete();
            return  response(['deleted' => $isDeleted], $isDeleted ? 200 : 404);
        }
    }
}
