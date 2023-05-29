<?php
use App\Http\Controllers\CustomerController;



initializeRoutes('Http/Controllers');
//

Route::get('/{customer}', [CustomerController::class, 'show']);
