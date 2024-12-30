<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Client\ResturantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientEmailVerificationNotificationController;
use App\Http\Controllers\ClientEmailVerificationPromptController;
use App\Http\Controllers\ClientVerifyEmailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;



// ==========================================================================================================================================

// All Admin Routes
require __DIR__ . '/admin.php';

// ==========================================================================================================================================
// All Client Routes
require __DIR__ . '/client.php';

// ==========================================================================================================================================
// All Users Routes
require __DIR__ . '/user.php';


