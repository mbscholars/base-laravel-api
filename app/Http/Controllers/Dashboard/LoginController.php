<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Resources\LoginResource;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @httpMethod GET
     * @middleware auth:sanctum
     * Display a listing of the resource.
     */
    public function session()
    {
        return new LoginResource(Auth::user());
    }

    /**
     * @httpMethod POST
     * Store a newly created resource in storage.
     */
    public function store(LoginRequest $request)
    {
        $data = $request->validated();

        if(!Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 'active'])){
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        return new LoginResource($user);

    }




    /**
     * @middleware auth:sanctum
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Auth::logout();
        return response()->json(['message' => 'Logout successful'], 200);
    }
}
