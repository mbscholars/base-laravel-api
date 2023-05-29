<?php

return [
    'modules' => [
        App\Modules\Customer\Providers\CustomerServiceProvider::class => [
            'migrations' => true,

        ],

    ],
    'register_route_models' => true
];
