<?php

namespace App\Modules\User\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \App\Modules\User\Contracts\User::class => \App\Modules\User\Models\User::class
    ];

}
