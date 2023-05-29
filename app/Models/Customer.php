<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\CustomerProxy;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Customer extends CustomerProxy
{
    use HasFactory;
}
