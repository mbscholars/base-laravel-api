<?php
namespace App\Modules\Customer\Providers;

use Illuminate\Routing\Router;
use Konekt\Concord\BaseModuleServiceProvider;

class CustomerServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \App\Modules\Customer\Contracts\Customer::class => \App\Modules\Customer\Models\Customer::class
    ];




}
