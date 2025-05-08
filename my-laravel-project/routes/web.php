<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/


// Include admin and intern routes
require __DIR__.'/admin.php';
require __DIR__.'/intern.php';
