<?php
use App\Http\Controllers\HomeController;





initializeRoutes('Http/Controllers');
//

Route::fallback([HomeController::class, 'index']);


