<?php
use App\Modules\User\Providers\ModuleServiceProvider;



return [
    'modules' => [
         ModuleServiceProvider::class => [
            'migrations' => true
        ],

    ]
];
