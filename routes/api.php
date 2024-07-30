<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\CurrencyController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\SliderController;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[AuthController::class,'register']);

Route::post('/forgot-password',[AuthController::class,'forgotPassword']);
Route::get('/password-reset', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password-reset-store', [AuthController::class, 'resetStore']);
Route::post('/login',[AuthController::class,'login']);

 Route::get('/popup-details', [AuthController::class, 'popup']);




Route::middleware('auth:api')->group(function(){
    //send otp 
    Route::get('/send-otp', [AuthController::class, 'OtpSend']);
   
    
    //user biz wallet balance 
    

      
      
    
     Route::get('/user-profile', [AuthController::class, 'UserProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    Route::post('/logout',[AuthController::class,'logout']);
});

//admin routes

//user list api
Route::middleware(['auth:api','admin'])->group(function(){
  
    Route::get('/customer-lists', [UserController::class, 'index']);
   
     //admin add balance 
    Route::post('/admin-add-balance', [AuthController::class, 'addBalance']);
    Route::post('/admin/user-update', [AuthController::class, 'UserUpdate']);
    
    //admin currency settings
    Route::post('/currency/store', [CurrencyController::class, 'store']);
    Route::get('/currencies', [CurrencyController::class, 'index']);
    Route::post('/currency/update', [CurrencyController::class, 'update']);
    Route::post('/currency/delete', [CurrencyController::class, 'delete']);
    
    //admin category settings 
    Route::post('/category/store', [CategoryController::class, 'store']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/category/update', [CategoryController::class, 'update']);
    Route::post('/category/delete', [CategoryController::class, 'delete']);
     //admin slider settings 
    Route::post('/slider/store', [SliderController::class, 'store']);
    Route::get('/sliders', [SliderController::class, 'index']);
    Route::post('/slider/update', [SliderController::class, 'update']);
    Route::post('/slider/delete', [SliderController::class, 'delete']);
 

   

});
