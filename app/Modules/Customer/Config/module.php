<?php

use App\Modules\Customer\Providers\CustomerServiceProvider;

return [
    'modules' => [
         CustomerServiceProvider::class => [
            'migrations' => true
        ],

    ]
];
