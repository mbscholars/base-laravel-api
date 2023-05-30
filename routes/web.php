<?php
use App\Http\Controllers\HomeController;




Route::fallback([HomeController::class, 'index']);


