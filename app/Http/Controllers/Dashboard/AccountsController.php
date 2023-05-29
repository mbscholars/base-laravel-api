<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterRequest;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function store(CustomerRegisterRequest $request)
    {
        echo 'index';
    }


}
