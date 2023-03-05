<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('api', ['except' => ['add_bank', 'edit_bank']]);
    }

    // public function index(){
    //     $data = Bank::all();
    // }

    public function add_bank()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'name' => 'required',
                'acronym' => 'required',
                'code' => 'required',
                'icon' => 'required',
                'status' => 'required',
                'createBy' => 'required',
                'updatedBy' => 'required'
            ]
        );
        if ($validator->fails()) {
            // return response()->json($validator->messages());

            return response()->json([
                'status' => false,
                'error' => false,
                'message' => 'Error',
                'data' => null,
            ], 200);
        }

        $bank = Bank::create([
            'name' => request('name'),
            'acronym' => request('acronym'),
            'code' => request('code'),
            'icon' => request('icon'),
            'status' => request('status'),
            'createBy' => request('createBy'),
            'updatedBy' => request('updatedBy')
        ]);

        if ($bank) {
            // return response()->json(['message' => 'Pendaftaran']);

            return response()->json([
                'status' => true,
                'error' => false,
                'message' => 'success',
                'data' => $bank,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'error' => false,
                'message' => 'Error',
                'data' => null,
            ], 200);
        }
    }


    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
}