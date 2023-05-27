<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * @httpMethod GET
 * @middleware role:admin,manager
 */
class CustomerController extends Controller
{
    /**
     * @httpMethod GET index
     * @middleware can:customer.read,customer.delete
     *
     * Display a listing of the resource.
     */
    public function index(User $user, Request $request)
    {
        echo 'index';
    }

    /**
     * Store a newly created resource in storage.
     * @middleware can:customer.store
     * @httpMethod POST
     */
    public function store(Request $request)
    {
        echo 'store ';
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user , Request $request)
    {
        echo 'show';
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * @ignore
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
